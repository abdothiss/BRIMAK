<?php
// dashboards/admin.php (Definitive, Unabbreviated, Translated, and Commented Version)
require_once 'includes/template_parts.php';
$user = get_user();
$current_view = $_GET['view'] ?? 'dashboard';
?>
<div class="space-y-6">
    <?php
    // This switch statement decides which content block to show
    // based on the URL provided by the new menu.
    switch ($current_view):

    // ===================================================================
    //  CASE 1: USER MANAGEMENT VIEW
    // ===================================================================
    case 'users':
        $all_users = $conn->query("SELECT id, name, username, role, section, is_active FROM users ORDER BY role, name")->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800"><?= t('admin_users_title') ?></h2>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0 mb-6">
            <h3 class="text-xl font-bold"><?= t('admin_users_heading') ?></h3>
            <button id="add-user-btn" class="w-full md:w-auto flex items-center justify-center space-x-2 px-4 py-2 bg-brick-red text-white rounded-md hover:bg-red-800 shadow-sm">
                <?= icon_plus('w-5 h-5') ?>
                <span><?= t('admin_users_add_button') ?></span>
            </button>
        </div>
            <div class="space-y-4">
                <?php foreach ($all_users as $u): ?>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 grid grid-cols-2 sm:grid-cols-4 gap-4 items-center">
                        <div class="col-span-2 sm:col-span-1"><p class="font-bold text-gray-900"><?= e($u['name']) ?></p><p class="text-sm text-gray-500">@<?= e($u['username']) ?></p></div>
                        <div class="col-span-2 sm:col-span-1"><p class="font-semibold text-gray-700"><?= t('role_' . strtolower(e($u['role']))) ?></p><p class="text-sm text-gray-500"><?= $u['section'] ? t('admin_users_section') . ' ' . e($u['section']) : 'N/A' ?></p></div>
                        <div class="flex justify-start sm:justify-center"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $u['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>"><?= $u['is_active'] ? t('admin_users_status_active') : t('admin_users_status_inactive') ?></span></div>
                        <div class="col-span-2 sm:col-span-1 flex justify-end items-center space-x-2 flex-wrap gap-2">
                            <button class="edit-user-btn text-sm px-3 py-1 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200" data-user='<?= json_encode($u) ?>'><?= t('admin_users_action_edit') ?></button>
                            <button class="reset-pw-btn text-sm px-3 py-1 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200" data-userid="<?= $u['id'] ?>" data-username="<?= e($u['username']) ?>"><?= t('admin_users_action_reset_pw') ?></button>
                            <?php if ($u['id'] !== $user['id']): ?><button class="delete-user-btn text-sm px-3 py-1 bg-red-100 text-danger-red rounded-md hover:bg-red-200" data-userid="<?= $u['id'] ?>" data-username="<?= e($u['username']) ?>"><?= t('admin_users_action_delete') ?></button><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
                <!-- =================================================================== -->
        <!-- USER MANAGEMENT MODALS (Corrected and Final Version) -->
        <!-- =================================================================== -->

        <!-- Add/Edit User Modal -->
        <div id="user-modal" 
             class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden"
             data-add-title="<?= t('admin_users_modal_add_title') ?>"
             data-edit-title="<?= t('admin_users_modal_edit_title') ?>">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
                <form id="user-form" action="actions/user_action.php" method="POST">
                    <div class="relative bg-brick-red p-4 rounded-t-lg">
                        <h2 id="user-modal-title" class="text-xl font-bold text-white text-center"></h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <input type="hidden" name="action" id="user-form-action" value="add_user">
                        <input type="hidden" name="user_id" id="user-form-id" value="">
                        <div><label class="block text-sm font-medium"><?= t('admin_users_modal_full_name') ?></label><input type="text" name="name" id="user-form-name" required class="mt-1 block w-full border p-2 rounded-md"></div>
                        <div><label class="block text-sm font-medium"><?= t('admin_users_modal_username') ?></label><input type="text" name="username" id="user-form-username" required class="mt-1 block w-full border p-2 rounded-md"></div>
                        <div id="password-field-container"><label class="block text-sm font-medium"><?= t('admin_users_modal_password') ?></label><input type="password" name="password" id="user-form-password" required class="mt-1 block w-full border p-2 rounded-md"></div>
                        <div><label class="block text-sm font-medium"><?= t('admin_users_modal_role') ?></label><select name="role" id="user-form-role" required class="mt-1 block w-full border p-2 rounded-md"></select></div>
                        <div><label class="block text-sm font-medium"><?= t('admin_users_modal_section') ?></label><select name="section" id="user-form-section" class="mt-1 block w-full border p-2 rounded-md"><option value="null"><?= t('admin_users_modal_section_none') ?></option><option value="A">A</option><option value="B">B</option></select></div>
                    </div>
                    <div class="flex justify-end space-x-3 p-4 bg-gray-50 border-t">
                        <button type="button" class="close-user-modal px-4 py-2 bg-gray-200 rounded-md"><?= t('admin_users_modal_button_cancel') ?></button>
                        <button type="submit" class="px-4 py-2 bg-brick-red text-white rounded-md"><?= t('admin_users_modal_button_save') ?></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete User Confirmation Modal -->
        <div id="delete-user-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
                <form action="actions/user_action.php" method="POST">
                    <input type="hidden" name="action" value="delete_user">
                    <input type="hidden" name="user_id" id="delete-user-id" value="">
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold"><?= t('admin_users_modal_delete_confirm') ?></h3>
                        <p class="my-2"><?= t('admin_users_modal_delete_text') ?> <strong id="delete-username"></strong></p>
                    </div>
                    <div class="flex justify-center space-x-4 p-4 bg-gray-50 border-t">
                        <button type="button" class="close-delete-modal px-6 py-2 bg-gray-200 rounded-md"><?= t('admin_users_modal_button_cancel') ?></button>
                        <button type="submit" class="px-6 py-2 bg-danger-red text-white rounded-md"><?= t('admin_users_action_delete') ?></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reset Password Confirmation Modal -->
        <div id="reset-pw-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
                <form action="actions/user_action.php" method="POST">
                    <input type="hidden" name="action" value="reset_password">
                    <input type="hidden" name="user_id" id="reset-pw-id" value="">
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold"><?= t('admin_users_modal_reset_confirm') ?></h3>
                        <p class="my-2"><?= t('admin_users_modal_reset_text') ?> <strong id="reset-pw-username"></strong> <?= t('admin_users_modal_reset_default') ?></p>
                    </div>
                    <div class="flex justify-center space-x-4 p-4 bg-gray-50 border-t">
                        <button type="button" class="close-reset-modal px-6 py-2 bg-gray-200 rounded-md"><?= t('admin_users_modal_button_cancel') ?></button>
                        <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-md">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Template for JavaScript Role Translations -->
        <div id="role-translations" class="hidden">
            <?php
            $translated_roles = [];
            foreach (ALL_ROLES as $role) {
                $translated_roles[$role] = t('role_' . strtolower($role));
            }
            echo json_encode($translated_roles);
            ?>
        </div>
        <?php
        break; // End the 'users' case

    // ===================================================================
    //  CASE 2: COMMAND HISTORY VIEW
    // ===================================================================
    case 'history':
    $search_term = $_GET['search'] ?? '';
    
   
    $history_sql = "
        SELECT c.* FROM commands c
        WHERE c.status IN ('Completed', 'Declined', 'Cancelled')
    ";
    $params = [];
    $types = '';

    
    if (!empty($search_term)) {
        $safe_search = '%' . $search_term . '%';
        $history_sql .= " AND (c.command_uid LIKE ? OR c.client_name LIKE ? OR c.client_phone LIKE ?)";
        
        $params = [$safe_search, $safe_search, $safe_search];
        $types = 'sss';
    }

    
    $history_sql .= " ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($history_sql);

    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    
    $stmt->execute();
    $history_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800"><?= t('history_title_admin') ?></h2>
        <div class="bg-white p-4 rounded-lg shadow-sm my-6">
            <form action="index.php" method="GET" class="search-container">
                <input type="hidden" name="view" value="history">
                <input type="text" name="search" class="search-input" placeholder="<?= t('history_search_placeholder_admin') ?>" value="<?= e($search_term) ?>">
                <button type="submit" class="search-button"><?= icon_search('w-5 h-5') ?></button>
            </form>
            <?php if ($user['role'] === 'Admin' && count($history_commands) > 0): ?>
            <div class="border-t mt-4 pt-4 flex justify-center">
                <button type="button" id="open-delete-all-modal-btn" class="flex items-center space-x-2 px-4 py-2 bg-red-100 text-red-700 font-semibold text-sm rounded-md hover:bg-red-200">
                    <?= icon_trash('w-5 h-5') ?>
                    <span><?= t('history_delete_all_button') ?></span>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <div class="space-y-2">
            <?php if(count($history_commands) > 0): ?>
                <?php foreach ($history_commands as $command):
                    
                    $status_text = $command['status'];
                    if ($status_text === 'Archived') {
                        $last_real_status_query = $conn->query("SELECT step_name FROM command_history WHERE command_id = " . (int)$command['id'] . " ORDER BY completed_at DESC LIMIT 1");
                        $status_text = ($last_real_status_query->num_rows > 0) ? 'Completed' : 'Declined';
                    }
                    echo render_history_drawer($command, $user, $status_text);
                endforeach; ?>
            <?php else: ?>
                <p class="text-center py-10 bg-white rounded-lg shadow-sm"><?= t('history_none_found_admin') ?></p>
            <?php endif; ?>
        </div>
        <?php
        break;

    default:
        // ================== LIVE COMMANDS VIEW (THIS IS THE FIX) ==================
        $filter_client_name = $_GET['filter_client_name'] ?? '';
        $filter_status = $_GET['filter_status'] ?? 'All';
        
        // ** THE FIX: "Live" statuses are now explicitly defined **
        $live_statuses = ['PendingApproval', 'InProgress', 'Completed', 'Declined',];

        $live_sql = "
            SELECT c.*, 
                   (SELECT COUNT(*) FROM command_history ch WHERE ch.command_id = c.id) as history_count,
                   (SELECT GROUP_CONCAT(ch.step_name SEPARATOR ',') FROM command_history ch WHERE ch.command_id = c.id ORDER BY ch.completed_at) as completed_steps
            FROM commands c 
            LEFT JOIN user_command_views ucv ON c.id = ucv.command_id AND ucv.user_id = ? 
            WHERE ucv.id IS NULL
        ";
        $params = [$user['id']]; $types = 'i';

        // ** THE FIX: The filter logic is now smarter **
        if ($filter_status !== 'All' && in_array($filter_status, $live_statuses)) {
            $live_sql .= " AND c.status = ?";
            $params[] = $filter_status; $types .= 's';
        } else {
            // If the filter is 'All' or an invalid status, we only show the live statuses.
            $live_sql .= " AND c.status IN ('" . implode("','", $live_statuses) . "')";
        }
        
        if (!empty($filter_client_name)) {
            $live_sql .= " AND c.client_name LIKE ?";
            $params[] = '%' . $filter_client_name . '%'; $types .= 's';
        }
        
        $live_sql .= " ORDER BY c.created_at DESC";
        
        $stmt = $conn->prepare($live_sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $live_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800"><?= t('admin_live_title') ?></h2>
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm space-y-4">
            <form action="index.php" method="GET" class="search-container"><input type="hidden" name="view" value="dashboard"><input type="hidden" name="filter_status" value="<?= e($filter_status) ?>"><input type="text" name="filter_client_name" class="search-input" placeholder="<?= t('admin_live_search_placeholder') ?>" value="<?= e($filter_client_name) ?>"><button type="submit" class="search-button"><?= icon_search('w-5 h-5') ?></button></form>
            <div>
                <div class="border-t border-gray-200 my-4"></div>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $statuses_to_show = ['All', 'PendingApproval', 'InProgress', 'Completed', 'Declined'];
                    foreach ($statuses_to_show as $status):
                        $status_key = 'status_' . strtolower(str_replace(' ', '', $status));
                        $status_text = t($status_key);
                        $is_active = ($filter_status === $status);
                        $bg_color = $is_active ? 'bg-brick-red text-white' : 'bg-white text-gray-700 hover:bg-gray-200 border border-gray-300';
                        $link = "index.php?view=dashboard&filter_status=" . e($status) . "&filter_client_name=" . urlencode($filter_client_name);
                    ?>
                        <a href="<?= $link ?>" class="px-3 py-1 text-sm font-medium rounded-full shadow-sm <?= $bg_color ?>"><?= e($status_text) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php if (count($live_commands) > 0): ?>
                <?php foreach ($live_commands as $command):
                    $actions_html = '';
                    if ($command['status'] === 'Completed' || $command['status'] === 'Declined') { $actions_html .= '<form action="actions/command_action.php" method="POST" class="inline-block" title="'.t('admin_live_archive_title').'"><input type="hidden" name="view" value="dashboard"><input type="hidden" name="command_id" value="'.e($command['id']).'"><button type="submit" name="action" value="archive" class="p-2 text-gray-400 hover:text-red-600">'.icon_x('w-5 h-5').'</button></form>'; }
                    echo render_command_card($command, $user, $actions_html);
                endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 col-span-full text-center py-10 bg-white rounded-lg shadow-sm"><?= t('admin_live_none_found') ?></p>
            <?php endif; ?>
        </div>
        <?php
        break; // End the default case
    endswitch; // End the switch statement
    ?>
</div>