<?php
// dashboards/settings.php (Definitive, Corrected Version)
$user = get_user();
$profile_error = $_SESSION['profile_error'] ?? null;
$profile_success = $_SESSION['profile_success'] ?? null;
unset($_SESSION['profile_error'], $_SESSION['profile_success']);
?>
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-4">
        <div class="w-16 h-16 bg-brick-red rounded-full flex items-center justify-center"><?= icon_settings('w-8 h-8 text-white') ?></div>
        <div><h2 class="text-3xl font-extrabold text-gray-800">Settings</h2><p class="text-gray-500">Manage your account and preferences</p></div>
    </div>
    
    <?php if ($profile_success): ?><div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert"><p><?= e($profile_success) ?></p></div><?php endif; ?>
    <?php if ($profile_error): ?><div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert"><p><?= e($profile_error) ?></p></div><?php endif; ?>

    <div class="space-y-4">
        <!-- Name Section -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div id="display-name" class="flex justify-between items-center">
                <div><p class="text-sm font-semibold text-gray-500">Name</p><p class="text-gray-800"><?= e($user['name']) ?></p></div>
                <button id="edit-name-btn" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Edit</button>
            </div>
            <form id="form-name" action="actions/settings_action.php" method="POST" class="hidden mt-4 space-y-2">
                <input type="hidden" name="action" value="change_name">
                <!-- ** THIS IS THE REDIRECT FIX ** -->
                <input type="hidden" name="redirect_to" value="settings">
                <input type="text" name="new_name" class="w-full border p-2 rounded-md" value="<?= e($user['name']) ?>">
                <div class="flex justify-end space-x-2"><button type="button" class="cancel-btn px-4 py-1 bg-gray-200 text-sm rounded-md">Cancel</button><button type="submit" class="px-4 py-1 bg-brick-red text-white text-sm rounded-md">Save</button></div>
            </form>
        </div>

        <!-- Username Section -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div id="display-username" class="flex justify-between items-center">
                <div><p class="text-sm font-semibold text-gray-500">Username</p><p class="text-gray-800">@<?= e($user['username']) ?></p></div>
                <button id="edit-username-btn" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Edit</button>
            </div>
            <form id="form-username" action="actions/settings_action.php" method="POST" class="hidden mt-4 space-y-2">
                <input type="hidden" name="action" value="change_username">
                <!-- ** THIS IS THE REDIRECT FIX ** -->
                <input type="hidden" name="redirect_to" value="settings">
                <input type="text" name="new_username" class="w-full border p-2 rounded-md" value="<?= e($user['username']) ?>">
                <div class="flex justify-end space-x-2"><button type="button" class="cancel-btn px-4 py-1 bg-gray-200 text-sm rounded-md">Cancel</button><button type="submit" class="px-4 py-1 bg-brick-red text-white text-sm rounded-md">Save</button></div>
            </form>
        </div>

        <!-- Password Section -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div id="display-password" class="flex justify-between items-center">
                <div><p class="text-sm font-semibold text-gray-500">Password</p><p class="text-gray-800">••••••••</p></div>
                <button id="edit-password-btn" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Edit</button>
            </div>
            <form id="form-password" action="actions/settings_action.php" method="POST" class="hidden mt-4 space-y-2">
                <input type="hidden" name="action" value="change_password">
                <!-- ** THIS IS THE REDIRECT FIX ** -->
                <input type="hidden" name="redirect_to" value="settings">
                <div><label class="block text-xs font-medium">Current Password</label><input type="password" name="current_password" required class="w-full border p-2 rounded-md"></div>
                <div><label class="block text-xs font-medium">New Password</label><input type="password" name="new_password" required class="w-full border p-2 rounded-md"></div>
                <div class="flex justify-end space-x-2"><button type="button" class="cancel-btn px-4 py-1 bg-gray-200 text-sm rounded-md">Cancel</button><button type="submit" class="px-4 py-1 bg-brick-red text-white text-sm rounded-md">Save</button></div>
            </form>
        </div>

        <!-- Appearance Section -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4 border-b pb-2">Appearance</h3>
            <div class="space-y-4 pt-2">
                <div class="flex items-center justify-between"><span class="text-sm font-medium text-gray-700">Language</span><span class="text-sm text-gray-500">English (FR coming soon)</span></div>
                <div class="flex items-center justify-between"><span class="text-sm font-medium text-gray-700">Dark Mode</span>
                    <button id="dark-mode-toggle" class="w-12 h-6 flex items-center bg-gray-300 rounded-full p-1 duration-300 ease-in-out"><div class="w-4 h-4 bg-white rounded-full shadow-md transform duration-300 ease-in-out"></div></button>
                </div>
            </div>
        </div>
    </div>
</div>