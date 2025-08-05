
<?php
// --- TEMPORARY DEBUGGING CODE ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// --- END OF DEBUGGING CODE ---


// actions/settings_action.php (Definitive, Corrected Version)
require_once '../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../index.php'); exit(); }

$user = get_user();
$action = $_POST['action'] ?? null;
$redirect_to = $_POST['redirect_to'] ?? 'dashboard';
$redirect_url = '../index.php?view=' . urlencode($redirect_to);

// ===================================================================
//  ACTION: TOGGLE DARK MODE 
// ===================================================================
if ($action === 'toggle_dark_mode') {
    $new_theme = $_POST['theme'] ?? 'light';
    
    if (in_array($new_theme, ['light', 'dark'])) {
        // This query will now succeed because the 'theme' column exists.
        $stmt = $conn->prepare("UPDATE users SET theme = ? WHERE id = ?");
        $stmt->bind_param("si", $new_theme, $user['id']);
        $stmt->execute();
        
        $_SESSION['user']['theme'] = $new_theme;
        $_SESSION['user_theme'] = $new_theme;
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'new_theme' => $new_theme]);
        exit(); // Stop the script immediately after sending the JSON.
    }
}

// ===================================================================
//  This part is for the other form submissions.
// ===================================================================
$redirect_to = $_POST['redirect_to'] ?? 'dashboard';
$redirect_url = '../index.php?view=' . urlencode($redirect_to);

// --- ACTION: CHANGE FULL NAME ---
if ($action === 'change_name') {
    $new_name = trim($_POST['new_name'] ?? '');
    if (empty($new_name)) {
        // ** THE FIX: Use the dictionary key instead of the full sentence **
        $_SESSION['profile_error'] = 'profile_error_name_empty';
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $new_name, $user['id']);
        $stmt->execute();
        $_SESSION['user']['name'] = $new_name;
        // ** THE FIX: Use the dictionary key **
        $_SESSION['profile_success'] = 'profile_success_name_updated';
    }
}

// --- ACTION: CHANGE USERNAME ---
if ($action === 'change_username') {
    $new_username = trim($_POST['new_username'] ?? '');
    if (empty($new_username)) {
        // ** THE FIX: Use the dictionary key **
        $_SESSION['profile_error'] = 'profile_error_user_empty';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $new_username, $user['id']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            // ** THE FIX: Use the dictionary key **
            $_SESSION['profile_error'] = 'profile_error_user_taken';
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $new_username, $user['id']);
            $stmt->execute();
            $_SESSION['user']['username'] = $new_username;
            // ** THE FIX: Use the dictionary key **
            $_SESSION['profile_success'] = 'profile_success_user_updated';
        }
    }
}

// --- ACTION: CHANGE PASSWORD ---
if ($action === 'change_password') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    if (empty($current_password) || empty($new_password)) {
        // ** THE FIX: Use the dictionary key **
        $_SESSION['profile_error'] = 'profile_error_pass_empty';
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $db_password_hash = $stmt->get_result()->fetch_assoc()['password'];
        if (password_verify($current_password, $db_password_hash)) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_password_hash, $user['id']);
            $stmt->execute();
            // ** THE FIX: Use the dictionary key **
            $_SESSION['profile_success'] = 'profile_success_pass_updated';
        } else {
            // ** THE FIX: Use the dictionary key **
            $_SESSION['profile_error'] = 'profile_error_pass_incorrect';
        }
    }
}

header("Location: " . $redirect_url);
exit();