<?php
// dashboards/production.php (Definitive, Corrected Version)
require_once 'includes/template_parts.php';
$user = get_user();
$current_view = $_GET['view'] ?? 'dashboard';

if ($current_view === 'history') {
    // ** Worker-Specific History View **
    // Get search term
    $search_term = $_GET['search'] ?? '';
    
    // Build the worker-specific history query with search functionality
    $history_sql = "SELECT DISTINCT c.* FROM commands c JOIN command_history ch ON c.id = ch.command_id WHERE ch.completed_by_id = ?";
    $params = [$user['id']];
    $types = 'i';

    if (!empty($search_term)) {
        $safe_search = '%' . $conn->real_escape_string($search_term) . '%';
        // Workers can only search by Command ID
        $history_sql .= " AND c.command_uid LIKE ?";
        $params[] = $safe_search;
        $types .= 's';
    }
    $history_sql .= " ORDER BY ch.completed_at DESC";
    
    $stmt = $conn->prepare($history_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $history_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>
    <h2 class="text-3xl font-extrabold text-gray-800">Your Personal Command History</h2>
    
    <!-- ** NEW SEARCH & DELETE ALL SECTION ** -->
    <div class="bg-white p-4 rounded-lg shadow-sm my-6">
        <form action="index.php" method="GET" class="search-container">
            <input type="hidden" name="view" value="history">
            <input type="text" name="search" class="search-input" placeholder="Search by Command ID..." value="<?= e($search_term) ?>">
            <button type="submit" class="search-button">
                <?= icon_search('w-5 h-5') ?>
            </button>
        </form>
        <?php if (count($history_commands) > 0): ?>
        <div class="border-t mt-4 pt-4">
            <button id="open-delete-all-modal-btn" class="text-sm font-semibold text-red-600 hover:text-red-800">
                Delete All My History
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- History Drawer List -->
    <div class="space-y-2">
        <?php if(count($history_commands) > 0): ?>
            <?php foreach ($history_commands as $command):
                $status_text = $command['status'];
                echo render_history_drawer($command, $user, $status_text);
            endforeach; ?>
        <?php else: ?>
            <p class="text-center py-10 bg-white rounded-lg shadow-sm">You have no commands in your history yet.</p>
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
        <h2 class="text-3xl font-bold text-gray-800"><?= e($user['role']) ?> Dashboard</h2>
        <p class="text-lg text-gray-600">You have <?= count($my_commands) ?> task(s) to complete.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($my_commands) > 0): ?>
                <?php foreach ($my_commands as $command):
                    //
                    // ** THIS IS THE CRITICAL FIX **
                    // The "..." has been replaced with the full, unabbreviated HTML form.
                    //
                    $actions_html = '<form action="actions/command_action.php" method="POST" class="w-full">
                                        <input type="hidden" name="command_id" value="'.e($command['id']).'">
                                        <input type="hidden" name="command_type" value="'.e($command['type']).'">
                                        <input type="hidden" name="current_step" value="'.e($command['current_step']).'">
                                        <button type="submit" name="action" value="complete_task" class="w-full flex items-center justify-center space-x-3 px-6 py-3 bg-success-green text-white font-bold text-lg rounded-md hover:bg-green-700 transition-colors">
                                            '.icon_check('w-7 h-7').'<span>Task Complete</span>
                                        </button>
                                    </form>';
                    echo render_command_card($command, $user, $actions_html);
                endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center bg-white p-10 rounded-lg shadow-md"><p class="text-gray-500 text-xl">No pending tasks at the moment. Great job!</p></div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>