<?php
// dashboards/production.php (Definitive, Unabbreviated, Translated Version)
require_once 'includes/template_parts.php';
$user = get_user();
$current_view = $_GET['view'] ?? 'dashboard';

if ($current_view === 'history') {
    $search_term = $_GET['search'] ?? '';
    $history_sql = "
        SELECT DISTINCT c.* FROM commands c
        JOIN command_history ch ON c.id = ch.command_id
        LEFT JOIN user_command_views ucv ON c.id = ucv.command_id AND ucv.user_id = ?
        WHERE ch.completed_by_id = ? AND ucv.id IS NULL
    ";
    $params = [$user['id'], $user['id']];
    $types = 'ii';

    if (!empty($search_term)) {
        $history_sql .= " AND c.command_uid LIKE ?";
        $params[] = '%' . $search_term . '%';
        $types .= 's';
    }
    $history_sql .= " ORDER BY ch.completed_at DESC";
    
    $stmt = $conn->prepare($history_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $history_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>
    <h2 class="text-3xl font-extrabold text-gray-800"><?= t('history_title_personal') ?></h2>
    
    <div class="bg-white p-4 rounded-lg shadow-sm my-6">
        <form action="index.php" method="GET" class="search-container">
            <input type="hidden" name="view" value="history">
            <input type="text" name="search" class="search-input" placeholder="<?= t('history_search_placeholder_worker') ?>" value="<?= e($search_term) ?>">
            <button type="submit" class="search-button"><?= icon_search('w-5 h-5') ?></button>
        </form>
        <?php if (count($history_commands) > 0): ?>
            <!-- THIS IS THE DELETE ALL BUTTON -->
        <div class="border-t mt-4 pt-4 flex justify-center">
            
            <button type="button" id="open-delete-all-modal-btn"
                class="flex items-center space-x-2 px-4 py-2 bg-red-100 text-red-700 font-semibold text-sm rounded-md hover:bg-red-200">
                <?= icon_trash('w-5 h-5') ?>
                <span><?= t('history_delete_all_button') ?></span>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <div class="space-y-2">
        <?php if(count($history_commands) > 0): ?>
            <?php foreach ($history_commands as $command):
                echo render_history_drawer($command, $user, $command['status']);
            endforeach; ?>
        <?php else: ?>
            <p class="text-center py-10 bg-white rounded-lg shadow-sm"><?= t('history_none_found_personal') ?></p>
        <?php endif; ?>
    </div>
    <?php
} else {
    // ** Default "Current Tasks" View **
    $stmt = $conn->prepare("SELECT * FROM commands WHERE status = 'InProgress' AND current_step = ? AND type = ? ORDER BY created_at ASC");
    $stmt->bind_param("ss", $user['role'], $user['section']);
    $stmt->execute();
    $my_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="space-y-6">
        <h2 class="text-3xl font-bold text-gray-800"><?= e($user['role']) ?> <?= t('production_dashboard_title_suffix') ?></h2>
        <p class="text-lg text-gray-600"><?= str_replace('{count}', count($my_commands), t('production_tasks_to_complete')) ?></p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($my_commands) > 0): ?>
                <?php foreach ($my_commands as $command):
                    $actions_html = '<form action="actions/command_action.php" method="POST" class="w-full">
                                        <input type="hidden" name="command_id" value="'.e($command['id']).'">
                                        <input type="hidden" name="command_type" value="'.e($command['type']).'">
                                        <input type="hidden" name="current_step" value="'.e($command['current_step']).'">
                                        <button type="submit" name="action" value="complete_task" class="w-full flex items-center justify-center space-x-3 px-6 py-3 bg-success-green text-white font-bold text-lg rounded-md hover:bg-green-700 transition-colors">
                                            '.icon_check('w-7 h-7').'<span>'.t('production_task_complete_button').'</span>
                                        </button>
                                    </form>';
                    echo render_command_card($command, $user, $actions_html);
                endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center bg-white p-10 rounded-lg shadow-md"><p class="text-gray-500 text-xl"><?= t('production_no_pending_tasks') ?></p></div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>