<?php
require_once '../includes/functions.php';
require_login();
$redirect_view = $_POST['view'] ?? 'dashboard';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}

$user = get_user();
$action = $_POST['action'] ?? null;
$command_id = $_POST['command_id'] ?? null;

// --- CREATE or UPDATE a command (from Commercial modal) ---
if ($action === 'create' || $action === 'update') {
    if ($user['role'] !== 'Commercial' && $user['role'] !== 'Admin') {
        // unauthorized
        header('Location: ../index.php'); exit();
    }
    
    // Sanitize and validate inputs
        $type = $_POST['type']; // This is still being sent, but you might want to remove it
        $product_name = trim($_POST['product_name']);
        $arrival_date = $_POST['arrival_date'];
        $deadline_date = $_POST['deadline_date'];
        $client_name = $_POST['client_name'];
        $client_phone = $_POST['client_phone'];
        $additional_notes = $_POST['additional_notes'];

        $first_step = WORKFLOWS[$type][0];

        if ($action === 'create') {
            $command_uid = 'CMD' . time();
            $stmt = $conn->prepare("INSERT INTO commands (command_uid, type, product_name, arrival_date, deadline_date, client_name, client_phone, additional_notes, status, current_step, created_by_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'PendingApproval', ?, ?)");
            $stmt->bind_param("sssssssssi", $command_uid, $type, $product_name, $arrival_date, $deadline_date, $client_name, $client_phone, $additional_notes, $first_step, $user['id']);
            $stmt->execute();
            
        } elseif ($action === 'update' && $command_id) { // this is unsuseful for the moment, zid update lcommercial la bghitiha
            // This is for resubmitting a declined command
            $stmt = $conn->prepare("UPDATE commands SET type=?, product_name=?, arrival_date=?, deadline_date=?, client_name=?, client_phone=?, additional_notes=?, status='PendingApproval', current_step=?, decline_reason=NULL WHERE id=?");
            $stmt->bind_param("ssssssssi", $type, $product_name, $arrival_date, $deadline_date, $client_name, $client_phone, $additional_notes, $first_step, $command_id);
            $stmt->execute();
            
        }
}

// --- CHEF ACCEPT ---
if ($action === 'accept' && $user['role'] === 'Chef' && $command_id) {
    $command_type = $_POST['command_type'];
    $workflow = WORKFLOWS[$command_type];
    $next_step = $workflow[1];
    
    $stmt = $conn->prepare("UPDATE commands SET status = 'InProgress', current_step = ? WHERE id = ?");
    $stmt->bind_param("si", $next_step, $command_id);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO command_history (command_id, step_name, completed_by_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $command_id, $user['role'], $user['id']);
    $stmt->execute();
}

// --- CHEF DECLINE ---
if ($action === 'decline' && $user['role'] === 'Chef' && $command_id) {
    $reason = $_POST['decline_reason'];
    $stmt = $conn->prepare("UPDATE commands SET status = 'Declined', decline_reason = ?, current_step = NULL WHERE id = ?");
    $stmt->bind_param("si", $reason, $command_id);
    $stmt->execute();
    $stmt_history = $conn->prepare("INSERT INTO command_history (command_id, step_name, completed_by_id) VALUES (?, ?, ?)");
    $stmt_history->bind_param("isi", $command_id, $user['role'], $user['id']);
    $stmt_history->execute();
}

// --- PRODUCTION WORKER COMPLETE TASK ---
if ($action === 'complete_task' && $command_id) {
    $command_type = $_POST['command_type'];
    $current_step = $_POST['current_step'];
    $workflow = WORKFLOWS[$command_type];
    
    $current_step_index = array_search($current_step, $workflow);
    
    if ($current_step_index !== false) {
        // Add to history
        $stmt = $conn->prepare("INSERT INTO command_history (command_id, step_name, completed_by_id) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $command_id, $current_step, $user['id']);
        $stmt->execute();

        // Determine next step or completion
        if ($current_step_index === count($workflow) - 1) {
            // This was the final step
            $stmt = $conn->prepare("UPDATE commands SET status = 'Completed', current_step = NULL WHERE id = ?");
            $stmt->bind_param("i", $command_id);
            $stmt->execute();
        } else {
            // Move to next step
            $next_step = $workflow[$current_step_index + 1];
            $stmt = $conn->prepare("UPDATE commands SET current_step = ? WHERE id = ?");
            $stmt->bind_param("si", $next_step, $command_id);
            $stmt->execute();
        }
    }
}

if ($action === 'archive' && $command_id) {
    if (in_array($user['role'], ['Admin', 'Commercial'])) {
        // We simply create a record saying this user has dismissed this command.
        $stmt = $conn->prepare("INSERT IGNORE INTO user_command_views (user_id, command_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user['id'], $command_id);
        $stmt->execute();
    }
}

// --- "DELETES" (HIDES) ONE COMMAND FROM THE CURRENT USER'S HISTORY VIEW ---
if ($action === 'delete_history' && $command_id) {
    $stmt = $conn->prepare("INSERT IGNORE INTO user_command_views (user_id, command_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user['id'], $command_id);
    $stmt->execute();
}
if ($action === 'delete_all_history') {
    if (in_array($user['role'], ['Admin', 'Commercial'])) {
        $stmt = $conn->prepare("INSERT IGNORE INTO user_command_views (user_id, command_id) SELECT ?, id FROM commands WHERE status IN ('Completed', 'Declined')");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT IGNORE INTO user_command_views (user_id, command_id) SELECT ?, command_id FROM command_history WHERE completed_by_id = ?");
        $stmt->bind_param("ii", $user['id'], $user['id']);
        $stmt->execute();
    }
}

if ($action === 'cancel_command' && $command_id) {
    // Security: Only Admins and Commercials can cancel a command.
    if (in_array($user['role'], ['Admin', 'Commercial'])) {
        // This query sets the status to 'Cancelled' only if it's currently
        // in a state that is eligible for cancellation.
        $stmt = $conn->prepare("UPDATE commands SET status = 'Cancelled' WHERE id = ? AND status IN ('PendingApproval', 'InProgress', 'Paused')");
        $stmt->bind_param("i", $command_id);
        $stmt->execute();
    }
}

// ** THIS IS THE REDIRECT FIX, PART 2 **
// We now redirect to the correct view (e.g., index.php?view=history)
header('Location: ../index.php?view=' . urlencode($redirect_view));
exit();;