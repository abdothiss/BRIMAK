<?php
// dashboards/profile.php (Definitive Version)
$user = get_user();
$profile_error = $_SESSION['profile_error'] ?? null;
$profile_success = $_SESSION['profile_success'] ?? null;
unset($_SESSION['profile_error'], $_SESSION['profile_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Manage Profile - BRIMAK</title><link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100">

    <!-- The beautiful red header is unchanged and preserved -->
    <div class="bg-gradient-red-header text-white shadow-lg p-4 relative">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-4"><a href="index.php" class="p-2 -ml-2 rounded-full hover:bg-white/20"><?= icon_arrow_left('w-6 h-6') ?></a><span class="text-xl font-bold">Profile</span><div class="w-6 h-6"></div></div>
            <div class="text-center space-y-2"><div class="inline-block bg-white/20 px-4 py-2 rounded-full mb-2"><?= icon_user('w-10 h-10') ?></div><h2 class="text-2xl font-bold"><?= e($user['name']) ?></h2><p class="text-white/80">@<?= e($user['username']) ?></p></div>
            <div class="mt-4 pt-4 border-t border-white/20 text-center"><p class="text-sm font-semibold">Role: <span class="font-bold"><?= e($user['role']) ?></span><?php if ($user['section']): ?><span class="mx-2 text-white/50">|</span>Section: <span class="font-bold"><?= e($user['section']) ?></span><?php endif; ?></p></div>
        </div>
    </div>
    
    <div class="container mx-auto p-4 sm:p-6 space-y-4">
        <?php if ($profile_success): ?><div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert"><p><?= e($profile_success) ?></p></div><?php endif; ?>
        <?php if ($profile_error): ?><div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert"><p><?= e($profile_error) ?></p></div><?php endif; ?>

        <!-- ** NEW, CLEAN DISPLAY LIST ** -->
        <div class="bg-white p-4 rounded-lg shadow-md divide-y divide-gray-200">
            <!-- Name Item -->
            <button id="open-name-modal-btn" class="w-full flex justify-between items-center py-3 text-left">
                <div class="flex-grow"><p class="text-sm text-gray-500">Name</p><p class="font-semibold text-gray-800"><?= e($user['name']) ?></p></div>
                <span class="text-gray-400"><?= icon_chevron_right('w-5 h-5') ?></span>
            </button>
            <!-- Username Item -->
            <button id="open-username-modal-btn" class="w-full flex justify-between items-center py-3 text-left">
                <div class="flex-grow"><p class="text-sm text-gray-500">Username</p><p class="font-semibold text-gray-800">@<?= e($user['username']) ?></p></div>
                <span class="text-gray-400"><?= icon_chevron_right('w-5 h-5') ?></span>
            </button>
            <!-- Settings Item -->
            <button id="open-password-modal-btn" class="w-full flex justify-between items-center py-3 text-left">
                <p class="font-semibold text-gray-800">Settings</p>
                <span class="text-gray-400"><?= icon_chevron_right('w-5 h-5') ?></span>
            </button>
        </div>

        <!-- Logout Button -->
        <div class="bg-white p-4 rounded-lg shadow-md">
            <a href="logout.php" class="w-full flex justify-center items-center py-3 text-left font-semibold text-logout-red">Logout</a>
        </div>
    </div>

    <!-- The full, unabbreviated hidden modals -->
    <div id="change-name-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden"><div class="bg-white rounded-lg shadow-xl w-full max-w-sm"><div class="p-4 border-b flex justify-between items-center"><h2 class="text-xl font-bold">Change Full Name</h2><button class="close-modal-btn text-gray-500">×</button></div><form action="actions/profile_action.php" method="POST" class="p-6 space-y-4"><input type="hidden" name="action" value="change_name"><div><label class="block text-sm font-medium">New Full Name</label><input type="text" name="new_name" required class="mt-1 block w-full border p-2 rounded-md" value="<?= e($user['name']) ?>"></div><div class="flex justify-end space-x-3"><button type="button" class="close-modal-btn px-4 py-2 bg-gray-200 rounded-md">Cancel</button><button type="submit" class="px-4 py-2 bg-brick-red text-white rounded-md">Save Name</button></div></form></div></div>
    <div id="change-username-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden"><div class="bg-white rounded-lg shadow-xl w-full max-w-sm"><div class="p-4 border-b flex justify-between items-center"><h2 class="text-xl font-bold">Change Username</h2><button class="close-modal-btn text-gray-500">×</button></div><form action="actions/profile_action.php" method="POST" class="p-6 space-y-4"><input type="hidden" name="action" value="change_username"><div><label class="block text-sm font-medium text-gray-500">Current Username</label><input type="text" value="<?= e($user['username']) ?>" readonly class="mt-1 block w-full border p-2 rounded-md bg-gray-100"></div><div><label class="block text-sm font-medium">New Username</label><input type="text" name="new_username" required class="mt-1 block w-full border p-2 rounded-md"></div><div class="flex justify-end space-x-3"><button type="button" class="close-modal-btn px-4 py-2 bg-gray-200 rounded-md">Cancel</button><button type="submit" class="px-4 py-2 bg-brick-red text-white rounded-md">Save Username</button></div></form></div></div>
    <div id="change-password-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden"><div class="bg-white rounded-lg shadow-xl w-full max-w-sm"><div class="p-4 border-b flex justify-between items-center"><h2 class="text-xl font-bold">Settings</h2><button class="close-modal-btn text-gray-500">×</button></div><form action="actions/profile_action.php" method="POST" class="p-6 space-y-4 border-b"><h3 class="font-semibold">Change Password</h3><input type="hidden" name="action" value="change_password"><div><label class="block text-sm font-medium">Current Password</label><input type="password" name="current_password" required class="mt-1 block w-full border p-2 rounded-md"></div><div><label class="block text-sm font-medium">New Password</label><input type="password" name="new_password" required class="mt-1 block w-full border p-2 rounded-md"></div><div class="flex justify-end"><button type="submit" class="px-4 py-2 bg-brick-red text-white rounded-md text-sm">Update Password</button></div></form><div class="p-6 space-y-4"><h3 class="font-semibold">Appearance</h3><div class="flex justify-between items-center"><label class="block text-sm font-medium">Language</label><span class="text-sm text-gray-500">English (coming soon)</span></div><div class="flex justify-between items-center"><label class="block text-sm font-medium">Dark Mode</label><span class="text-sm text-gray-500">Off (coming soon)</span></div></div><div class="p-4 bg-gray-50 flex justify-end"><button type="button" class="close-modal-btn px-4 py-2 bg-gray-200 rounded-md">Close</button></div></div></div>
</body>
</html>