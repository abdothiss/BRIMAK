<?php // dashboards/admin_views/_users.php
$all_users = $conn->query("SELECT * FROM users ORDER BY role, name")->fetch_all(MYSQLI_ASSOC);
?>
<h2 class="text-3xl font-extrabold text-gray-800">User Management</h2>
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold">Users</h3>
        <button id="add-user-btn" class="...">Add User</button>
    </div>
    <div class="space-y-4">
        <?php foreach ($all_users as $u): ?>
            <div class="bg-gray-50 ...">
                <!-- User list item HTML -->
            </div>
        <?php endforeach; ?>
    </div>
</div>

        <!-- All the user management modals from your file -->
        <!-- ====== MODALS FOR USER MANAGEMENT (THE MISSING PIECE) ====== -->

<!-- Add/Edit User Modal -->
<div id="user-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="flex justify-between items-center p-4 border-b">
            <h2 id="user-modal-title" class="text-xl font-bold text-gray-800">Add New User</h2>
            <button class="close-user-modal text-gray-500 hover:text-gray-800"><?= icon_x() ?></button>
        </div>
        <div class="p-6">
            <form id="user-form" action="actions/user_action.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" id="user-form-action" value="add_user">
                <input type="hidden" name="user_id" id="user-form-id" value="">
                <div><label class="block text-sm font-medium">Full Name</label><input type="text" name="name" id="user-form-name" required class="mt-1 block w-full border p-2 rounded-md"></div>
                <div><label class="block text-sm font-medium">Username</label><input type="text" name="username" id="user-form-username" required class="mt-1 block w-full border p-2 rounded-md"></div>
                <div id="password-field-container"><label class="block text-sm font-medium">Password</label><input type="password" name="password" id="user-form-password" required class="mt-1 block w-full border p-2 rounded-md"></div>
                <div><label class="block text-sm font-medium">Role</label><select name="role" id="user-form-role" required class="mt-1 block w-full border p-2 rounded-md"><?php foreach(ALL_ROLES as $role_option): ?><option value="<?= $role_option ?>"><?= $role_option ?></option><?php endforeach; ?></select></div>
                <div><label class="block text-sm font-medium">Section</label><select name="section" id="user-form-section" class="mt-1 block w-full border p-2 rounded-md"><option value="null">None</option><option value="A">A</option><option value="B">B</option></select></div>
                <div class="flex justify-end space-x-3 pt-4"><button type="button" class="close-user-modal px-4 py-2 bg-gray-200 rounded-md">Cancel</button><button type="submit" class="px-4 py-2 bg-brick-red text-white rounded-md">Save User</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Confirmation Modal -->
<div id="delete-user-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <form action="actions/user_action.php" method="POST">
            <input type="hidden" name="action" value="delete_user">
            <input type="hidden" name="user_id" id="delete-user-id" value="">
            <div class="p-6 text-center"><h3 class="text-lg font-bold">Are you sure?</h3><p class="my-2">Do you really want to delete the user <strong id="delete-username"></strong>? This process cannot be undone.</p></div>
            <div class="flex justify-center space-x-4 p-4 bg-gray-50"><button type="button" class="close-delete-modal px-6 py-2 bg-gray-200 rounded-md">Cancel</button><button type="submit" class="px-6 py-2 bg-danger-red text-white rounded-md">Delete</button></div>
        </form>
    </div>
</div>

<!-- Reset Password Confirmation Modal -->
<div id="reset-pw-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <form action="actions/user_action.php" method="POST">
            <input type="hidden" name="action" value="reset_password">
            <input type="hidden" name="user_id" id="reset-pw-id" value="">
            <div class="p-6 text-center"><h3 class="text-lg font-bold">Reset Password?</h3><p class="my-2">This will reset the password for <strong id="reset-pw-username"></strong> to the default "<strong>password</strong>".</p></div>
            <div class="flex justify-center space-x-4 p-4 bg-gray-50"><button type="button" class="close-reset-modal px-6 py-2 bg-gray-200 rounded-md">Cancel</button><button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-md">Reset</button></div>
        </form>
    </div>
</div>