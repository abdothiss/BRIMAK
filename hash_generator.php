<?php
// A simple script to generate a secure password hash.
// The password we want to hash is 'password'
$passwordToHash = 'd';

// Hash the password using PHP's recommended BCRYPT algorithm
$hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);

// Display the generated hash
echo "<h1>Password Hash Generator</h1>";
echo "<p>Password to hash: <strong>" . htmlspecialchars($passwordToHash) . "</strong></p>";
echo "<p>Generated Hash:</p>";
echo "<textarea rows='3' cols='70' readonly>" . htmlspecialchars($hashedPassword) . "</textarea>";
echo "<p>Copy the hash above and paste it into the 'password' column for the 'admin' user in your database.</p>";
?>