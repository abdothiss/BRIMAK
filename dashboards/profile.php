<?php
// dashboards/profile.php (Definitive Version)

// This file is now a complete page, so it needs access to the user data directly.
$user = get_user();

// Get any success or error messages from the session
$profile_error = $_SESSION['profile_error'] ?? null;
$profile_success = $_SESSION['profile_success'] ?? null;
unset($_SESSION['profile_error'], $_SESSION['profile_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile - BRIMAK</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100">

    <!-- The new full-width profile header -->
    <div class="bg-gradient-red-header text-white shadow-lg p-1 relative">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-6">
                <a href="index.php" class="p-2 -ml-2 rounded-full hover:bg-white/20"><?= icon_arrow_left('w-6 h-6') ?></a>
                <span class="text-xl font-bold">Profile</span>
                <div class="w-6 h-6"></div> <!-- Spacer to keep title centered -->
            </div>
            <div class="text-center">
                <div class="inline-block bg-white/20 p-1 rounded-full mb-2">
                    <?= icon_user('w-24 h-24') ?>
                </div>
                <h2 class="text-2xl font-bold"><?= e($user['name']) ?></h2>
                <p class="text-white/80">@<?= e($user['username']) ?></p>
            </div>
            <div class="flex justify-around mt-6 pt-4 border-t border-white/20">
                <div class="text-center">
                    <p class="text-xl font-bold"><?= e($user['role']) ?></p>
                    <p class="text-xs text-white/70">Role</p>
                </div>
                <?php if ($user['section']): ?>
                <div class="text-center">
                    <p class="text-xl font-bold">Section <?= e($user['section']) ?></p>
                    <p class="text-xs text-white/70">Section</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <!-- The new, professional forms container -->
    <div class="container mx-auto p-6 space-y-6">
        <!-- Display Success/Error Messages -->
        <?php if ($profile_success): ?><div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert"><p><?= e($profile_success) ?></p></div><?php endif; ?>
        <?php if ($profile_error): ?><div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert"><p><?= e($profile_error) ?></p></div><?php endif; ?>

    <!-- ** NEW: Change Name Form ** -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4 border-b pb-2">Change Full Name</h3>
            <form action="actions/profile_action.php" method="POST" class="space-y-4 pt-2">
                <input type="hidden" name="action" value="change_name">
                <div><label class="block text-sm font-medium text-gray-700">New Full Name</label><input type="text" name="new_name" required class="mt-1 block w-full border border-gray-300 p-2 rounded-md" value="<?= e($user['name']) ?>"></div>
                <div class="flex justify-end pt-2"><button type="submit" class="px-6 py-2 bg-brick-red text-white font-semibold rounded-md hover:bg-red-800">Save Name</button></div>
            </form>
        </div>

        <!-- ** REDESIGNED: Change Username Form ** -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4 border-b pb-2">Change Username</h3>
            <form action="actions/profile_action.php" method="POST" class="space-y-4 pt-2">
                <input type="hidden" name="action" value="change_username">
                <div><label class="block text-sm font-medium text-gray-500">Current Username</label><input type="text" value="<?= e($user['username']) ?>" readonly class="mt-1 block w-full border p-2 rounded-md bg-gray-100 text-gray-500"></div>
                <div><label class="block text-sm font-medium text-gray-700">New Username</label><input type="text" name="new_username" required class="mt-1 block w-full border border-gray-300 p-2 rounded-md"></div>
                <div class="flex justify-end pt-2"><button type="submit" class="px-6 py-2 bg-brick-red text-white font-semibold rounded-md hover:bg-red-800">Save Username</button></div>
            </form>
        </div>

        <!-- ** REDESIGNED: Change Password Form ** -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4 border-b pb-2">Change Password</h3>
            <form action="actions/profile_action.php" method="POST" class="space-y-4 pt-2">
                <input type="hidden" name="action" value="change_password">
                <div><label class="block text-sm font-medium text-gray-700">Current Password</label><input type="password" name="current_password" required class="mt-1 block w-full border border-gray-300 p-2 rounded-md"></div>
                <div><label class="block text-sm font-medium text-gray-700">New Password</label><input type="password" name="new_password" required class="mt-1 block w-full border border-gray-300 p-2 rounded-md"></div>
                <div class="flex justify-end pt-2"><button type="submit" class="px-6 py-2 bg-brick-red text-white font-semibold rounded-md hover:bg-red-800">Update Password</button></div>
            </form>
        </div>
    </div>

</body>
</html>