<?php
// dashboards/admin.php (Definitive, Complete Version)

require_once 'includes/template_parts.php';
$user = get_user();

// Get the current view from the URL, default to 'dashboard' (live commands)
$current_view = $_GET['view'] ?? 'dashboard';
?>

<div class="space-y-6">
    
    <?php
    // This switch statement decides which content block to show
    // based on the URL provided by the new menu.
    switch ($current_view):

    // ================== CASE 1: USER MANAGEMENT VIEW ==================
    case 'users':
        $all_users = $conn->query("SELECT id, name, username, role, section, is_active FROM users ORDER BY role, name")->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800">User Management</h2>
        
        <!-- This is the full, working User Management HTML -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Users</h3>
                <button id="add-user-btn" class="flex items-center space-x-2 px-4 py-2 bg-brick-red text-white rounded-md hover:bg-red-800 shadow-sm">
                    <?= icon_plus('w-5 h-5') ?>
                    <span>Add User</span>
                </button>
            </div>
            
            <div class="space-y-4">
                <?php foreach ($all_users as $u): ?>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 grid grid-cols-2 sm:grid-cols-4 gap-4 items-center">
                        <div class="col-span-2 sm:col-span-1"><p class="font-bold text-gray-900"><?= e($u['name']) ?></p><p class="text-sm text-gray-500">@<?= e($u['username']) ?></p></div>
                        <div class="col-span-2 sm:col-span-1"><p class="font-semibold text-gray-700"><?= e($u['role']) ?></p><p class="text-sm text-gray-500"><?= $u['section'] ? 'Section ' . e($u['section']) : 'N/A' ?></p></div>
                        <div class="flex justify-start sm:justify-center"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $u['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>"><?= $u['is_active'] ? 'Active' : 'Inactive' ?></span></div>
                        <div class="col-span-2 sm:col-span-1 flex justify-end items-center space-x-2 flex-wrap gap-2">
                            <button class="edit-user-btn text-sm px-3 py-1 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200" data-user='<?= json_encode($u) ?>'>Edit</button>
                            <button class="reset-pw-btn text-sm px-3 py-1 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200" data-userid="<?= $u['id'] ?>" data-username="<?= e($u['username']) ?>">Reset PW</button>
                            <?php if ($u['id'] !== $user['id']): ?>
                                <button class="delete-user-btn text-sm px-3 py-1 bg-red-100 text-danger-red rounded-md hover:bg-red-200" data-userid="<?= $u['id'] ?>" data-username="<?= e($u['username']) ?>">Delete</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- The full, unabbreviated HTML for all User Management modals -->
        <div id="user-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md"><div class="flex justify-between items-center p-4 border-b"><h2 id="user-modal-title" class="text-xl font-bold text-gray-800">Add New User</h2><button class="close-user-modal text-gray-500 hover:text-gray-800"><?= icon_x() ?></button></div><div class="p-6"><form id="user-form" action="actions/user_action.php" method="POST" class="space-y-4"><input type="hidden" name="action" id="user-form-action" value="add_user"><input type="hidden" name="user_id" id="user-form-id" value=""><div><label class="block text-sm font-medium">Full Name</label><input type="text" name="name" id="user-form-name" required class="mt-1 block w-full border p-2 rounded-md"></div><div><label class="block text-sm font-medium">Username</label><input type="text" name="username" id="user-form-username" required class="mt-1 block w-full border p-2 rounded-md"></div><div id="password-field-container"><label class="block text-sm font-medium">Password</label><input type="password" name="password" id="user-form-password" required class="mt-1 block w-full border p-2 rounded-md"></div><div><label class="block text-sm font-medium">Role</label><select name="role" id="user-form-role" required class="mt-1 block w-full border p-2 rounded-md"><?php foreach(ALL_ROLES as $role_option): ?><option value="<?= $role_option ?>"><?= $role_option ?></option><?php endforeach; ?></select></div><div><label class="block text-sm font-medium">Section</label><select name="section" id="user-form-section" class="mt-1 block w-full border p-2 rounded-md"><option value="null">None</option><option value="A">A</option><option value="B">B</option></select></div><div class="flex justify-end space-x-3 pt-4"><button type="button" class="close-user-modal px-4 py-2 bg-gray-200 rounded-md">Cancel</button><button type="submit" class="px-4 py-2 bg-brick-red text-white rounded-md">Save User</button></div></form></div></div>
        </div>
        <div id="delete-user-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md"><form action="actions/user_action.php" method="POST"><input type="hidden" name="action" value="delete_user"><input type="hidden" name="user_id" id="delete-user-id" value=""><div class="p-6 text-center"><h3 class="text-lg font-bold">Are you sure?</h3><p class="my-2">Do you really want to delete the user <strong id="delete-username"></strong>? This process cannot be undone.</p></div><div class="flex justify-center space-x-4 p-4 bg-gray-50"><button type="button" class="close-delete-modal px-6 py-2 bg-gray-200 rounded-md">Cancel</button><button type="submit" class="px-6 py-2 bg-danger-red text-white rounded-md">Delete</button></div></form></div>
        </div>
        <div id="reset-pw-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md"><form action="actions/user_action.php" method="POST"><input type="hidden" name="action" value="reset_password"><input type="hidden" name="user_id" id="reset-pw-id" value=""><div class="p-6 text-center"><h3 class="text-lg font-bold">Reset Password?</h3><p class="my-2">This will reset the password for <strong id="reset-pw-username"></strong> to the default "<strong>password</strong>".</p></div><div class="flex justify-center space-x-4 p-4 bg-gray-50"><button type="button" class="close-reset-modal px-6 py-2 bg-gray-200 rounded-md">Cancel</button><button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-md">Reset</button></div></form></div>
        </div>
        <?php
        break;

    // ================== CASE 2: COMMAND HISTORY VIEW ==================
    case 'history':
        $history_commands = $conn->query("SELECT * FROM commands WHERE status IN ('Completed', 'Declined', 'Archived') ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800">Command History</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 pt-6">
            <?php if (count($history_commands) > 0): ?>
                <?php foreach ($history_commands as $command):
                    echo render_command_card($command, $user, '');
                endforeach; ?>
            <?php else: ?>
                 <p class="text-gray-500 col-span-full text-center py-10 bg-white rounded-lg shadow-sm">No command history found.</p>
            <?php endif; ?>
        </div>
        <?php
        break;

    // ================== CASE 3: DEFAULT DASHBOARD (LIVE COMMANDS) ==================
    default:
        $filter_client_name = $_GET['filter_client_name'] ?? '';
        $filter_status = $_GET['filter_status'] ?? 'All';
        $where_conditions = ["c.status != 'Archived'"];
        if (!empty($filter_client_name)) { $where_conditions[] = "c.client_name LIKE '%" . $conn->real_escape_string($filter_client_name) . "%'"; }
        if ($filter_status !== 'All' && in_array($filter_status, ALL_STATUSES)) { $where_conditions[] = "c.status = '" . $conn->real_escape_string($filter_status) . "'"; }
        $sql_where = "WHERE " . implode(' AND ', $where_conditions);
        $live_commands_sql = "SELECT c.*, (SELECT COUNT(*) FROM command_history ch WHERE ch.command_id = c.id) as history_count FROM commands c $sql_where ORDER BY c.created_at DESC";
        $live_commands = $conn->query($live_commands_sql)->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800">Live Commands</h2>
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm space-y-4">
            <form action="index.php" method="GET">
                <input type="hidden" name="view" value="dashboard">
                <input type="hidden" name="filter_status" value="<?= e($filter_status) ?>">
                <div class="flex flex-col sm:flex-row items-center gap-2">
                    <input type="text" name="filter_client_name" class="w-full p-2 border border-gray-300 rounded-md shadow-sm" placeholder="Search by client name..." value="<?= e($filter_client_name) ?>">
                    <button type="submit" class="w-full sm:w-auto justify-center flex items-center px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm hover:bg-gray-700">Search</button>
                </div>
            </form>
            <div>
                <div class="border-t border-gray-200 my-4"></div>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $statuses_to_show = array_merge(['All'], ALL_STATUSES);
                    foreach ($statuses_to_show as $status):
                        if ($status === 'Archived') continue;
                        $is_active = ($filter_status === $status);
                        $bg_color = $is_active ? 'bg-brick-red text-white' : 'bg-white text-gray-700 hover:bg-gray-200 border border-gray-300';
                        $link = "index.php?view=dashboard&filter_status=" . e($status) . "&filter_client_name=" . urlencode($filter_client_name);
                    ?>
                        <a href="<?= $link ?>" class="px-3 py-1 text-sm font-medium rounded-full shadow-sm <?= $bg_color ?>"><?= e($status) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php if (count($live_commands) > 0): ?>
                <?php foreach ($live_commands as $command):
                    $actions_html = '';
                    if ($command['status'] === 'Completed' || $command['status'] === 'Declined') {
                        $actions_html .= '<form action="actions/command_action.php" method="POST" class="inline-block" title="Archive: Remove from live view"><input type="hidden" name="command_id" value="'.e($command['id']).'"><button type="submit" name="action" value="archive" class="p-2 text-gray-400 hover:text-red-600">'.icon_x('w-5 h-5').'</button></form>';
                    }
                    echo render_command_card($command, $user, $actions_html);
                endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 col-span-full text-center py-10 bg-white rounded-lg shadow-sm">No commands found for the current filter criteria.</p>
            <?php endif; ?>
        </div>
        <?php
        break;
    endswitch;
    ?>
</div>