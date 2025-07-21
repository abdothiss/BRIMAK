<?php
require_once '../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}

$user = get_user();
$action = $_POST['action'] ?? null;

// --- ACTION: CHANGE USERNAME ---
if ($action === 'change_username') {
    $new_username = trim($_POST['new_username'] ?? '');

    if (empty($new_username)) {
        $_SESSION['profile_error'] = 'New username cannot be empty.';
    } else {
        // Check if username is already taken by another user
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $new_username, $user['id']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['profile_error'] = 'That username is already taken.';
        } else {
            // Update the username
            $update_stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_username, $user['id']);
            $update_stmt->execute();
            
            // IMPORTANT: Update the session so the change is reflected immediately
            $_SESSION['user']['username'] = $new_username;
            $_SESSION['profile_success'] = 'Username successfully updated!';
        }
    }
}

// --- ACTION: CHANGE PASSWORD ---
if ($action === 'change_password') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (empty($current_password) || empty($new_password)) {
        $_SESSION['profile_error'] = 'Please fill in all password fields.';
    } else {
        // Get current hashed password from DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $db_password_hash = $stmt->get_result()->fetch_assoc()['password'];
        
        // Verify the current password is correct
        if (password_verify($current_password, $db_password_hash)) {
            // Hash the new password and update the database
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_password_hash, $user['id']);
            $update_stmt->execute();
            $_SESSION['profile_success'] = 'Password successfully updated!';
        } else {
            $_SESSION['profile_error'] = 'Your current password was incorrect.';
        }
    }
}

// Redirect back to the dashboard
header('Location: ../index.php?view=profile');
exit();