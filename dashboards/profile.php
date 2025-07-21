<?php

// Get any success or error messages from the session
$profile_error = $_SESSION['profile_error'] ?? null;
$profile_success = $_SESSION['profile_success'] ?? null;
unset($_SESSION['profile_error'], $_SESSION['profile_success']);

$user = get_user(); // This function is available from index.php
?>

<div class="space-y-8 max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800">Manage Profile</h2>

    <!-- Display Success/Error Messages -->
    <?php if ($profile_success): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
            <p><?= e($profile_success) ?></p>
        </div>
    <?php endif; ?>
    <?php if ($profile_error): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
            <p><?= e($profile_error) ?></p>
        </div>
    <?php endif; ?>

    <!-- Change Username Form -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-4">Change Username</h3>
        <form action="actions/profile_action.php" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="change_username">
            <div>
                <label for="current_username" class="block text-sm font-medium text-gray-500">Current Username</label>
                <input type="text" id="current_username" value="<?= e($user['username']) ?>" readonly class="mt-1 block w-full border p-2 rounded-md bg-gray-100">
            </div>
            <div>
                <label for="new_username" class="block text-sm font-medium text-gray-700">New Username</label>
                <input type="text" id="new_username" name="new_username" required class="mt-1 block w-full border p-2 rounded-md">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-brick-red text-white rounded-md">Save Username</button>
            </div>
        </form>
    </div>

    <!-- Change Password Form -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-4">Change Password</h3>
        <form action="actions/profile_action.php" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="change_password">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input type="password" id="current_password" name="current_password" required class="mt-1 block w-full border p-2 rounded-md">
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" id="new_password" name="new_password" required class="mt-1 block w-full border p-2 rounded-md">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-brick-red text-white rounded-md">Update Password</button>
            </div>
        </form>
    </div>
</div>