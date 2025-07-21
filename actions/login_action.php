<?php
// This is the ONLY setup line this file should have.
// functions.php will handle starting the session and connecting to the database in the correct order.
require_once '../includes/functions.php';

// Check if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit();
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$remember_me = isset($_POST['remember_me']);

// Because functions.php has already run require_once 'db.php',
// the $conn variable is guaranteed to be a valid connection object here.
// The fatal error will be resolved.

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password']) && $user['is_active']) {
        $_SESSION['user'] = $user;

        if ($remember_me) {
            // This function lives in functions.php and is ready to be used.
            create_remember_me_token($conn, $user['id']);
        }

        header("Location: ../index.php");
        exit();
    }
}

$_SESSION['error'] = 'Invalid credentials or inactive account.';
header("Location: ../login.php");
exit();