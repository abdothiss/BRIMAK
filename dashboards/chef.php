<?php
// dashboards/chef.php (Definitive, Corrected Version)
require_once 'includes/template_parts.php';
$user = get_user();
$current_view = $_GET['view'] ?? 'dashboard';

if ($current_view === 'history') {
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
    // ** Default "Pending Approval" View **
    $stmt = $conn->prepare("SELECT * FROM commands WHERE type = ? AND status = 'PendingApproval' ORDER BY created_at ASC");
    $stmt->bind_param("s", $user['section']);
    $stmt->execute();
    $my_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="space-y-6">
        <h2 class="text-3xl font-bold text-gray-800">Chef Dashboard - Section <?= e($user['section']) ?></h2>
        <p class="text-lg text-gray-600">You have <?= count($my_commands) ?> new command(s) awaiting approval.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($my_commands) > 0): ?>
                <?php foreach ($my_commands as $command):
                    //
                    // ** THIS IS THE CRITICAL FIX **
                    // The "..." has been replaced with the full, unabbreviated HTML for both buttons.
                    //
                    $actions_html = '<div class="w-full flex justify-around items-center">
                                      <button class="open-decline-modal flex items-center space-x-2 px-4 py-2 text-lg font-bold rounded-lg text-white bg-danger-red hover:bg-red-700" data-command-id="'.e($command['id']).'" data-command-uid="'.e($command['command_uid']).'">
                                          '.icon_x('w-6 h-6').'<span>Decline</span>
                                      </button>
                                      <form action="actions/command_action.php" method="POST" class="inline-block">
                                          <input type="hidden" name="command_id" value="'.e($command['id']).'">
                                          <input type="hidden" name="command_type" value="'.e($command['type']).'">
                                          <button type="submit" name="action" value="accept" class="flex items-center space-x-2 px-4 py-2 text-lg font-bold rounded-lg text-white bg-success-green hover:bg-green-700">
                                              '.icon_check('w-6 h-6').'<span>Accept</span>
                                          </button>
                                      </form>
                                  </div>';
                    echo render_command_card($command, $user, $actions_html);
                endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 col-span-full text-center py-10 bg-white rounded-lg shadow-sm">No new commands to review.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- The Decline Reason Modal (Unchanged and correct) -->
    <div id="decline-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center"><h2 id="decline-modal-title" class="text-xl font-bold">Decline Command</h2><button class="close-decline-modal text-gray-500 hover:text-gray-800"><?= icon_x() ?></button></div>
            <div class="p-6">
                <form action="actions/command_action.php" method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="decline"><input type="hidden" name="command_id" id="decline-command-id" value="">
                    <div><label for="declineReason" class="block text-sm font-medium">Reason for Declining</label><textarea id="declineReason" name="decline_reason" required rows="4" class="mt-1 block w-full border rounded-md p-2" placeholder="e.g., Wrong specs..."></textarea></div>
                    <div class="flex justify-end space-x-3 pt-2"><button type="button" class="close-decline-modal px-4 py-2 bg-gray-200 rounded-md">Cancel</button><button type="submit" class="px-4 py-2 bg-danger-red text-white rounded-md">Decline Command</button></div>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>

