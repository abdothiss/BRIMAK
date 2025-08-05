<?php
// dashboards/settings.php (Definitive, Unabbreviated, Icon-Driven Version)
$user = get_user();
$profile_error = $_SESSION['profile_error'] ?? null;
$profile_success = $_SESSION['profile_success'] ?? null;
unset($_SESSION['profile_error'], $_SESSION['profile_success']);
?>
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header Section (Unchanged) -->
    <div class="flex items-center space-x-4">
        <div class="w-16 h-16 bg-brick-red rounded-full flex items-center justify-center"><?= icon_settings('w-8 h-8 text-white') ?></div>
        <div><h2 class="text-3xl font-extrabold text-gray-800"><?= t('settings_page_heading') ?></h2><p class="text-gray-500"><?= t('settings_page_subheading') ?></p></div>
    </div>
    
    <!-- Success/Error Messages (Unchanged) -->
    <?php if ($profile_success): ?><div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert"><p><?= t($profile_success) ?></p></div><?php endif; ?>
    <?php if ($profile_error): ?><div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert"><p><?= t($profile_error) ?></p></div><?php endif; ?>

    <!-- ** THIS IS THE NEW, REDESIGNED EDIT SECTIONS ** -->
    <div class="space-y-4">
        <!-- Name Section -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div id="display-name" class="flex items-center">
                <div class="w-10 flex-shrink-0"><?= icon_user('w-6 h-6 text-gray-400') ?></div>
                <div class="flex-grow"><p class="text-sm font-semibold text-gray-500"><?= t('settings_display_name') ?></p><p class="text-gray-800"><?= e($user['name']) ?></p></div>
                <button id="edit-name-btn" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Edit</button>
            </div>
            <form id="form-name" action="actions/settings_action.php" method="POST" class="hidden mt-4 pt-4 border-t space-y-2">
                <input type="hidden" name="action" value="change_name"><input type="hidden" name="redirect_to" value="settings">
                <input type="text" name="new_name" class="w-full border p-2 rounded-md" value="<?= e($user['name']) ?>">
                <div class="flex justify-end space-x-2"><button type="button" class="cancel-btn px-4 py-1 bg-gray-200 text-sm rounded-md">Cancel</button><button type="submit" class="px-4 py-1 bg-brick-red text-white text-sm rounded-md">Save</button></div>
            </form>
        </div>

        <!-- Username Section -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div id="display-username" class="flex items-center">
                <div class="w-10 flex-shrink-0"><?= icon_at_sign('w-6 h-6 text-gray-400') ?></div>
                <div class="flex-grow"><p class="text-sm font-semibold text-gray-500"><?= t('settings_display_username') ?></p><p class="text-gray-800">@<?= e($user['username']) ?></p></div>
                <button id="edit-username-btn" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Edit</button>
            </div>
            <form id="form-username" action="actions/settings_action.php" method="POST" class="hidden mt-4 pt-4 border-t space-y-2">
                <input type="hidden" name="action" value="change_username"><input type="hidden" name="redirect_to" value="settings">
                <input type="text" name="new_username" class="w-full border p-2 rounded-md" value="<?= e($user['username']) ?>">
                <div class="flex justify-end space-x-2"><button type="button" class="cancel-btn px-4 py-1 bg-gray-200 text-sm rounded-md">Cancel</button><button type="submit" class="px-4 py-1 bg-brick-red text-white text-sm rounded-md">Save</button></div>
            </form>
        </div>

        <!-- Password Section -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div id="display-password" class="flex items-center">
                <div class="w-10 flex-shrink-0"><?= icon_lock('w-6 h-6 text-gray-400') ?></div>
                <div class="flex-grow"><p class="text-sm font-semibold text-gray-500"><?= t('settings_display_password') ?></p><p class="text-gray-800">••••••••</p></div>
                <button id="edit-password-btn" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Edit</button>
            </div>
            <form id="form-password" action="actions/settings_action.php" method="POST" class="hidden mt-4 pt-4 border-t space-y-2">
                <input type="hidden" name="action" value="change_password"><input type="hidden" name="redirect_to" value="settings">
                <div><label class="block text-xs font-medium">Current Password</label><input type="password" name="current_password" required class="w-full border p-2 rounded-md"></div>
                <div><label class="block text-xs font-medium">New Password</label><input type="password" name="new_password" required class="w-full border p-2 rounded-md"></div>
                <div class="flex justify-end space-x-2"><button type="button" class="cancel-btn px-4 py-1 bg-gray-200 text-sm rounded-md">Cancel</button><button type="submit" class="px-4 py-1 bg-brick-red text-white text-sm rounded-md">Save</button></div>
            </form>
        </div>

        <!-- Language Section -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="w-10 flex-shrink-0"><?= icon_language('w-6 h-6 text-gray-400') ?></div>
                <div class="flex-grow"><p class="text-sm font-semibold text-gray-500"><?= t('settings_language_label') ?></p></div>
                <div class="flex items-center space-x-2">
                    <a href="?view=settings&lang=en" class="px-3 py-1 text-sm rounded-md <?= ($lang_code === 'en' ? 'bg-brick-red text-white font-semibold' : 'bg-gray-200 hover:bg-gray-300') ?>">English</a>
                    <a href="?view=settings&lang=fr" class="px-3 py-1 text-sm rounded-md <?= ($lang_code === 'fr' ? 'bg-brick-red text-white font-semibold' : 'bg-gray-200 hover:bg-gray-300') ?>">Français</a>
                </div>
            </div>
        </div>
    </div>
</div>