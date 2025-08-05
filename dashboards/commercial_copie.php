<?php
// dashboards/commercial.php (Definitive, Unabbreviated, Translated Version)
require_once 'includes/template_parts.php';
$user = get_user();
$current_view = $_GET['view'] ?? 'dashboard';
?>
<div class="space-y-6">

    <?php if ($current_view === 'history'): ?>
        
        <!-- ================== COMMAND HISTORY VIEW ================== -->
        <?php
        $search_term = $_GET['search'] ?? '';
        $history_sql = "SELECT c.* FROM commands c LEFT JOIN user_command_views ucv ON c.id = ucv.command_id AND ucv.user_id = ? WHERE c.status IN ('Completed', 'Declined') AND ucv.id IS NULL";
        $params = [$user['id']]; $types = 'i';
        if (!empty($search_term)) { $safe_search = '%' . $search_term . '%'; $history_sql .= " AND (c.command_uid LIKE ? OR c.client_name LIKE ? OR c.client_phone LIKE ?)"; $params = array_merge($params, [$safe_search, $safe_search, $safe_search]); $types .= 'sss'; }
        $history_sql .= " ORDER BY c.created_at DESC";
        $stmt = $conn->prepare($history_sql); $stmt->bind_param($types, ...$params); $stmt->execute();
        $history_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800"><?= t('history_title_admin') ?></h2>
        <div class="bg-white p-4 rounded-lg shadow-sm my-6">
            <form action="index.php" method="GET" class="search-container">
                <input type="hidden" name="view" value="history">
                <input type="text" name="search" class="search-input" placeholder="<?= t('history_search_placeholder_admin') ?>" value="<?= e($search_term) ?>">
                <button type="submit" class="search-button"><?= icon_search('w-5 h-5') ?></button>
            </form>
            <?php if (count($history_commands) > 0): ?>
            <div class="border-t mt-4 pt-4">
                <form id="delete-all-form" action="actions/command_action.php" method="POST">
                    <input type="hidden" name="action" value="delete_all_history">
                    <input type="hidden" name="view" value="history">
                    <button type="submit" id="delete-all-history-btn" class="text-sm font-semibold text-red-600 hover:text-red-800"><?= t('history_delete_all_button') ?></button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <div class="space-y-2">
            <?php if(count($history_commands) > 0): ?>
                <?php foreach ($history_commands as $command): echo render_history_drawer($command, $user, $command['status']); endforeach; ?>
            <?php else: ?>
                <p class="text-center py-10 bg-white rounded-lg shadow-sm"><?= t('history_none_found_admin') ?></p>
            <?php endif; ?>
        </div>

    <?php else: // This is the default 'dashboard' view ?>

        <!-- ================== LIVE COMMANDS VIEW ================== -->
        <?php
        $filter_client_name = $_GET['filter_client_name'] ?? '';
        $filter_status = $_GET['filter_status'] ?? 'All';
        $live_sql = "SELECT c.*, (SELECT COUNT(*) FROM command_history ch WHERE ch.command_id = c.id) as history_count FROM commands c LEFT JOIN user_command_views ucv ON c.id = ucv.command_id AND ucv.user_id = ? WHERE ucv.id IS NULL";
        $params = [$user['id']]; $types = 'i';
        if (!empty($filter_client_name)) { $live_sql .= " AND c.client_name LIKE ?"; $params[] = '%' . $filter_client_name . '%'; $types .= 's'; }
        if ($filter_status !== 'All') { $live_sql .= " AND c.status = ?"; $params[] = $filter_status; $types .= 's'; }
        $live_sql .= " ORDER BY c.created_at DESC";
        $stmt = $conn->prepare($live_sql); $stmt->bind_param($types, ...$params); $stmt->execute();
        $my_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        ?>
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-800"><?= t('commercial_live_title') ?></h2>
            <button id="open-create-modal-btn" class="w-full md:w-auto flex items-center justify-center space-x-2 px-6 py-3 bg-brick-red text-white font-semibold rounded-lg shadow-md hover:bg-red-800 transition-colors"><?= icon_plus('w-6 h-6') ?> <span><?= t('commercial_create_button') ?></span></button>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm space-y-4">
            <form action="index.php" method="GET" class="search-container">
                <input type="hidden" name="view" value="dashboard"><input type="hidden" name="filter_status" value="<?= e($filter_status) ?>">
                <input type="text" name="filter_client_name" class="search-input" placeholder="<?= t('commercial_search_placeholder') ?>" value="<?= e($filter_client_name) ?>">
                <button type="submit" class="search-button"><?= icon_search('w-5 h-5') ?></button>
            </form>
            <div>
                <div class="border-t border-gray-200 my-4"></div>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $statuses_to_show = array_merge(['All'], ALL_STATUSES);
                    foreach ($statuses_to_show as $status):
                        if ($status === 'Archived' || $status === 'Paused') continue;
                        $status_key = 'status_' . strtolower(str_replace(' ', '', $status));
                        $status_text = t($status_key);
                        $is_active = ($filter_status === $status);
                        $bg_color = $is_active ? 'bg-brick-red text-white' : 'bg-white text-gray-700 hover:bg-gray-200 border';
                        $link = "index.php?view=dashboard&filter_status=" . e($status);
                    ?>
                        <a href="<?= $link ?>" class="px-3 py-1 text-sm font-medium rounded-full shadow-sm <?= $bg_color ?>"><?= e($status_text) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-6">
            <?php if (count($my_commands) > 0): ?>
                <?php foreach ($my_commands as $command):
                    $actions_html = '';
                    if ($command['status'] === 'Declined') { $actions_html .= '<button class="edit-command-btn flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-md bg-blue-100 text-blue-800 hover:bg-blue-200" data-command=\''.json_encode($command).'\'>'.icon_edit('w-4 h-4').'<span>'.t('commercial_modal_action_modify').'</span></button>'; }
                    if ($command['status'] === 'Completed' || $command['status'] === 'Declined') { $actions_html .= '<form action="actions/command_action.php" method="POST" class="inline-block" title="'.t('admin_live_archive_title').'"><input type="hidden" name="view" value="dashboard"><input type="hidden" name="command_id" value="'.e($command['id']).'"><button type="submit" name="action" value="archive" class="p-2 text-gray-400 hover:text-red-600">'.icon_x('w-5 h-5').'</button></form>'; }
                    echo render_command_card($command, $user, $actions_html);
                endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 col-span-full text-center py-10"><?= t('commercial_none_found') ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<div id="command-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-full overflow-y-auto">
    <div class="flex justify-between items-center p-4 border-b"><h2 id="modal-title" class="text-xl font-bold text-gray-800"><?= t('commercial_modal_create_title') ?></h2><button class="close-command-modal text-gray-500 hover:text-gray-800"><?= icon_x() ?></button></div>
    <div class="p-6">
      <form id="command-form" action="actions/command_action.php" method="POST" class="space-y-4">
        <input type="hidden" name="action" id="form-action" value="create"><input type="hidden" name="command_id" id="form-command-id" value="">
        <div><label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_type_label') ?></label><select name="type" id="form-type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"><option value="A"><?= t('commercial_modal_type_a') ?></option><option value="B"><?= t('commercial_modal_type_b') ?></option></select></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_dimensions_label') ?></label><input type="text" name="dimensions" id="form-dimensions" placeholder="<?= t('commercial_modal_dimensions_placeholder') ?>" class="mt-1 block w-full border p-2 rounded-md"></div><div><label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_quantity_label') ?></label><input type="number" name="quantity" id="form-quantity" class="mt-1 block w-full border p-2 rounded-md"></div></div>
        <div><label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_delivery_date_label') ?></label><input type="date" name="delivery_date" id="form-delivery-date" required class="mt-1 block w-full border p-2 rounded-md"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_client_name_label') ?></label><input type="text" name="client_name" id="form-client-name" required class="mt-1 block w-full border p-2 rounded-md"></div><div><label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_client_phone_label') ?></label><input type="text" name="client_phone" id="form-client-phone" required class="mt-1 block w-full border p-2 rounded-md"></div></div>
        <div><label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_notes_label') ?></label><textarea name="additional_notes" id="form-additional-notes" rows="3" class="mt-1 block w-full border p-2 rounded-md"></textarea></div>
        <div class="flex justify-end space-x-3 pt-4"><button type="button" class="close-command-modal px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"><?= t('commercial_modal_button_cancel') ?></button><button type="submit" class="px-4 py-2 bg-brick-red text-white rounded-md hover:bg-red-800"><?= t('commercial_modal_button_save') ?></button></div>
      </form>
    </div>
  </div>
</div>













<!-- 200 lines original  -->

<?php
// dashboards/commercial.php (Definitive Version)
require_once 'includes/template_parts.php';
$user = get_user();

// Get the current view from the URL, default to the main dashboard
$current_view = $_GET['view'] ?? 'dashboard';
?>

<div class="space-y-6">

    <?php if ($current_view === 'history'): ?>
       <?php 
        
        $search_term = $_GET['search'] ?? '';
    // This query is your correct, working query
    $history_sql = "
        SELECT c.* FROM commands c
        LEFT JOIN user_command_views ucv ON c.id = ucv.command_id AND ucv.user_id = ?
        WHERE c.status IN ('Completed', 'Declined') AND ucv.id IS NULL
    ";
    $params = [$user['id']];
    $types = 'i';

    if (!empty($search_term)) {
        $safe_search = '%' . $search_term . '%';
        $history_sql .= " AND (c.command_uid LIKE ? OR c.client_name LIKE ? OR c.client_phone LIKE ?)";
        $params = array_merge($params, [$safe_search, $safe_search, $safe_search]);
        $types .= 'sss';
    }
    $history_sql .= " ORDER BY c.created_at DESC";
    
    $stmt = $conn->prepare($history_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $history_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>
    <h2 class="text-3xl font-extrabold text-gray-800"><?= t('history_title_admin') ?></h2>
    
    <!-- ** THIS IS THE NEW, REDESIGNED SEARCH & DELETE SECTION ** -->
    <div class="bg-white p-4 rounded-lg shadow-sm my-6">
        <form action="index.php" method="GET" class="search-container">
            <input type="hidden" name="view" value="history">
            <input type="text" name="search" class="search-input" placeholder="<?= t('history_search_placeholder_admin') ?>" value="<?= e($search_term) ?>">
            <button type="submit" class="search-button">
                <?= icon_search('w-5 h-5') ?>
            </button>
        </form>
        
        <?php if (count($history_commands) > 0): ?>
        <div class="border-t mt-4 pt-4 flex justify-end">
            <!-- This is the new, redesigned button -->
            <button id="open-delete-all-modal-btn" class="flex items-center space-x-2 px-4 py-2 bg-red-100 text-red-700 font-semibold text-sm rounded-md hover:bg-red-200">
                <?= icon_trash('w-5 h-5') ?>
                <span><?= t('history_delete_all_button') ?></span>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- History Drawer List (The loop now calls the updated drawer function) -->
    <div class="space-y-2">
        <?php if(count($history_commands) > 0): ?>
            <?php foreach ($history_commands as $command): 
                echo render_history_drawer($command, $user, $command['status']); 
            endforeach; ?>
        <?php else: ?>
            <p class="text-center py-10 bg-white rounded-lg shadow-sm"><?= t('history_none_found_admin') ?></p>
        <?php endif; ?>
    </div>
    
     
    

    <?php else: // This is the default 'dashboard' view ?>

        <!-- ================== LIVE COMMANDS VIEW ================== -->
        <?php
        // ================== LIVE COMMANDS VIEW (THIS IS THE FIX) ==================
    $filter_client_name = $_GET['filter_client_name'] ?? '';
    $filter_status = $_GET['filter_status'] ?? 'All';
    
    $live_sql = "
        SELECT c.*, (SELECT COUNT(*) FROM command_history ch WHERE ch.command_id = c.id) as history_count
        FROM commands c LEFT JOIN user_command_views ucv ON c.id = ucv.command_id AND ucv.user_id = ? WHERE ucv.id IS NULL
    ";
    $params = [$user['id']]; $types = 'i';

    if ($filter_status !== 'All') {
    $live_sql .= " AND c.status = ?";
    $params[] = $filter_status;
    $types .= 's';
    }
    
    $live_sql .= " ORDER BY c.created_at DESC";
    
    $stmt = $conn->prepare($live_sql); $stmt->bind_param($types, ...$params); $stmt->execute();
    $my_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-800">Live Commands</h2>
            <button id="open-create-modal-btn" class="w-full md:w-auto flex items-center justify-center space-x-2 px-6 py-3 bg-brick-red text-white font-semibold rounded-lg shadow-md hover:bg-red-800 transition-colors"><?= icon_plus('w-6 h-6') ?> <span>Create New Command</span></button>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm space-y-4">
            <!-- ** FIX 2: The Search Bar is added ** -->
            <form action="index.php" method="GET" class="search-container">
                <input type="hidden" name="view" value="dashboard">
                <input type="hidden" name="filter_status" value="<?= e($filter_status) ?>">
                <input type="text" name="filter_client_name" class="search-input" placeholder="Search by client name..." value="<?= e($filter_client_name) ?>">
                <button type="submit" class="search-button"><?= icon_search('w-5 h-5') ?></button>
            </form>
            <div>
                <div class="border-t border-gray-200 my-4"></div>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $statuses_to_show = array_merge(['All'], ALL_STATUSES);
                    foreach ($statuses_to_show as $status):
                        // ** FIX 3: "Paused" and "Archived" buttons are now hidden **
                        if ($status === 'Archived' || $status === 'Paused') continue;
                        $is_active = ($filter_status === $status);
                        $bg_color = $is_active ? 'bg-brick-red text-white' : 'bg-white text-gray-700 hover:bg-gray-200 border';
                        $link = "index.php?view=dashboard&filter_status=" . e($status);
                    ?>
                        <a href="<?= $link ?>" class="px-3 py-1 text-sm font-medium rounded-full shadow-sm <?= $bg_color ?>"><?= e($status) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php if (count($my_commands) > 0): ?>
                <?php foreach ($my_commands as $command):
                    $actions_html = '';
                    if ($command['status'] === 'Completed' || $command['status'] === 'Declined') { $actions_html .= '<form action="actions/command_action.php" method="POST" class="inline-block"><input type="hidden" name="view" value="dashboard"><input type="hidden" name="command_id" value="'.e($command['id']).'"><button type="submit" name="action" value="archive" class="p-2 text-gray-400 hover:text-red-600">'.icon_x('w-5 h-5').'</button></form>'; }
                    echo render_command_card($command, $user, $actions_html);
                endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 col-span-full text-center py-10">No commands match the current filter.</p>
            <?php endif; ?>
        </div>
     <?php endif; ?>
</div>

    



<!-- =========== NEW: Command Create/Edit Modal is now part of the HTML =========== -->
<div id="command-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-full overflow-y-auto">
    <div class="flex justify-between items-center p-4 border-b">
      <h2 id="modal-title" class="text-xl font-bold text-gray-800">Create New Command</h2>
      <button class="close-command-modal text-gray-500 hover:text-gray-800"><?= icon_x() ?></button>
    </div>
    <div class="p-6">
      <form id="command-form" action="actions/command_action.php" method="POST" class="space-y-4">
        <!-- Hidden fields for action type and command ID -->
        <input type="hidden" name="action" id="form-action" value="create">
        <input type="hidden" name="command_id" id="form-command-id" value="">

        <div>
            <label class="block text-sm font-medium text-gray-700">Type</label>
            <select name="type" id="form-type" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                <option value="A">BRIMAK A</option>
                <option value="B">BRIMAK B</option>
            </select>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Dimensions</label>
                <input type="text" name="dimensions" id="form-dimensions" placeholder="e.g. 20cm x 10cm x 5cm" class="mt-1 block w-full border p-2 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" name="quantity" id="form-quantity" class="mt-1 block w-full border p-2 rounded-md">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Delivery Date</label>
            <input type="date" name="delivery_date" id="form-delivery-date" required class="mt-1 block w-full border p-2 rounded-md">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
             <div>
                <label class="block text-sm font-medium text-gray-700">Client Name</label>
                <input type="text" name="client_name" id="form-client-name" required class="mt-1 block w-full border p-2 rounded-md">
             </div>
             <div>
                <label class="block text-sm font-medium text-gray-700">Client Phone</label>
                <input type="text" name="client_phone" id="form-client-phone" required class="mt-1 block w-full border p-2 rounded-md">
             </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Additional Notes</label>
            <textarea name="additional_notes" id="form-additional-notes" rows="3" class="mt-1 block w-full border p-2 rounded-md"></textarea>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
            <button type="button" class="close-command-modal px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-brick-red text-white rounded-md hover:bg-red-800">Save Command</button>
        </div>
      </form>
    </div>
  </div>
</div>
