<?php
session_start();
require_once 'includes/db.php';

// Clear the "Remember Me" cookie from the browser and database
if (isset($_COOKIE['remember_me'])) {
    list($selector, $validator) = explode(':', $_COOKIE['remember_me']);
    $stmt = $conn->prepare("DELETE FROM auth_tokens WHERE selector = ?");
    $stmt->bind_param("s", $selector);
    $stmt->execute();
    setcookie('remember_me', '', time() - 3600, '/'); // Expire the cookie
}

// Standard logout procedure
session_unset();
session_destroy();
header("Location: login.php");
exit();