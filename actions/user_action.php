<?php
require_once '../includes/functions.php';
require_login();

// Security Check: Only Admins can perform these actions
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || get_user()['role'] !== 'Admin') {
    header('Location: ../index.php');
    exit();
}

$action = $_POST['action'] ?? null;
$user_id = $_POST['user_id'] ?? null;
$current_user_id = get_user()['id'];

// --- ADD or EDIT User ---
if ($action === 'add_user' || $action === 'edit_user') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $section = $_POST['section'] === 'null' ? NULL : $_POST['section'];
    
    if ($action === 'add_user') {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, username, password, role, section) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $username, $password, $role, $section);
        $stmt->execute();
    } elseif ($action === 'edit_user' && $user_id) {
        $stmt = $conn->prepare("UPDATE users SET name=?, username=?, role=?, section=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $username, $role, $section, $user_id);
        $stmt->execute();
    }
}

// --- DELETE User ---
if ($action === 'delete_user' && $user_id) {
    if ($user_id != $current_user_id) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
}

// --- RESET Password ---
if ($action === 'reset_password' && $user_id) {
    $default_password = password_hash('password', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $default_password, $user_id);
    $stmt->execute();
}

// Redirect back to the users tab
header('Location: ../index.php?view=users');
exit();