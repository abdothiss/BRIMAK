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

    // ===================================================================
    //  THIS IS THE CORRECTED LOGIC (Identical to Admin's)
    // ===================================================================
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
            <?php if (count($history_commands) > 0): ?>
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
        $live_sql = "SELECT c.*, (SELECT COUNT(*) FROM command_history ch WHERE ch.command_id = c.id) as history_count, (SELECT GROUP_CONCAT(ch.step_name SEPARATOR ',') FROM command_history ch WHERE ch.command_id = c.id ORDER BY ch.completed_at) as completed_steps FROM commands c LEFT JOIN user_command_views ucv ON c.id = ucv.command_id AND ucv.user_id = ? WHERE ucv.id IS NULL";
        $params = [$user['id']]; $types = 'i';
        if (!empty($filter_client_name)) { $live_sql .= " AND c.client_name LIKE ?"; $params[] = '%' . $filter_client_name . '%'; $types .= 's'; }
        $live_statuses = ['PendingApproval', 'InProgress', 'Completed', 'Declined'];
        if ($filter_status !== 'All' && in_array($filter_status, $live_statuses)) {
            $live_sql .= " AND c.status = ?"; $params[] = $filter_status; $types .= 's';
        } else {
            $live_sql .= " AND c.status IN ('" . implode("','", $live_statuses) . "')";
        }
        $live_sql .= " ORDER BY c.created_at DESC";
        $stmt = $conn->prepare($live_sql); $stmt->bind_param($types, ...$params); $stmt->execute();
        $my_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        ?>
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <h2 class="text-3xl font-bold text-gray-800"><?= t('commercial_live_title') ?></h2>
                <button id="open-create-modal-btn" class="w-full md:w-auto flex items-center justify-center space-x-2 px-6 py-3 bg-brick-red text-white font-semibold rounded-lg shadow-md hover:bg-red-800 transition-colors">
                    <?= icon_plus('w-6 h-6') ?>
                    <span><?= t('commercial_create_button') ?></span>
                </button>
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
                        $statuses_to_show = ['All', 'PendingApproval', 'InProgress', 'Completed', 'Declined'];
                        foreach ($statuses_to_show as $status):
                            $status_key = 'status_' . strtolower(str_replace(' ', '', $status));
                            $status_text = t($status_key);
                            $is_active = ($filter_status === $status);
                            $bg_color = $is_active ? 'bg-brick-red text-white' : 'bg-white text-gray-700 hover:bg-gray-200 border';
                            $link = "index.php?view=dashboard&filter_status=" . e($status) . "&filter_client_name=" . urlencode($filter_client_name);
                        ?>
                            <a href="<?= $link ?>" class="px-3 py-1 text-sm font-medium rounded-full shadow-sm <?= $bg_color ?>"><?= e($status_text) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <?php if (count($my_commands) > 0): ?>
                    <?php foreach ($my_commands as $command):
                        $actions_html = '';
                        if ($command['status'] === 'Completed' || $command['status'] === 'Declined') { 
                            $actions_html .= '<form action="actions/command_action.php" method="POST" class="inline-block"><input type="hidden" name="view" value="dashboard"><input type="hidden" name="command_id" value="'.e($command['id']).'"><button type="submit" name="action" value="archive" class="p-2 text-gray-400 hover:text-red-600" title="'.t('admin_live_archive_title').'">'.icon_x('w-5 h-5').'</button></form>'; 
                        }
                        echo render_command_card($command, $user, $actions_html);
                    endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 col-span-full text-center py-10"><?= t('commercial_none_found') ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>


<div id="command-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden"data-create-title="<?= t('commercial_modal_create_title') ?>"
     data-edit-title="<?= t('commercial_modal_edit_title') ?>">>
  <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-full overflow-y-auto">
    <!-- Header -->
    <div class="flex justify-center items-center p-4 border-b bg-[#B22222] rounded-t-lg">
      <h2 id="modal-title" class="text-xl font-bold text-white">
        <?= t('commercial_modal_create_title') ?>
      </h2>
    </div>

    <!-- Body -->
    <div class="p-6">
      <form id="command-form" action="actions/command_action.php" method="POST" class="space-y-4">
        <input type="hidden" name="action" id="form-action" value="create">
        <input type="hidden" name="command_id" id="form-command-id" value="">

        <!-- Type Dropdown -->
        <div>
          <label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_type_label') ?></label>
          <select name="type" id="form-type" required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-[#B22222] focus:border-[#B22222]">
            <option value="A"><?= t('commercial_modal_type_a') ?></option>
            <option value="B"><?= t('commercial_modal_type_b') ?></option>
          </select>
        </div>

        <!-- Product Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_product_label') ?></label>
          <input type="text" name="product_name" id="form-product-name" required
            class="mt-1 block w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#B22222] focus:border-[#B22222]"
            placeholder="e.g. Brique de 12 Creuse">
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_arrival_date_label') ?></label>
            <input type="date" name="arrival_date" id="form-arrival-date" required
              class="mt-1 block w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#B22222] focus:border-[#B22222]">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_deadline_date_label') ?></label>
            <input type="date" name="deadline_date" id="form-deadline-date" required
              class="mt-1 block w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#B22222] focus:border-[#B22222]">
          </div>
        </div>

        <!-- Client Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_client_name_label') ?></label>
            <input type="text" name="client_name" id="form-client-name" required
              class="mt-1 block w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#B22222] focus:border-[#B22222]">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_client_phone_label') ?></label>
            <input type="text" name="client_phone" id="form-client-phone" required
              class="mt-1 block w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#B22222] focus:border-[#B22222]">
          </div>
        </div>

        <!-- Notes -->
        <div>
          <label class="block text-sm font-medium text-gray-700"><?= t('commercial_modal_notes_label') ?></label>
          <textarea name="additional_notes" id="form-additional-notes" rows="3"
            class="mt-1 block w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#B22222] focus:border-[#B22222]"></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 pt-4">
          <button type="button"
            class="close-command-modal px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition"><?= t('commercial_modal_button_cancel') ?></button>
          <button type="submit"
            class="px-4 py-2 bg-[#B22222] text-white rounded-md hover:bg-[#8B1A1A] transition"><?= t('commercial_modal_button_save') ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
