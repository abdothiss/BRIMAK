<?php
// Included from index.php
require_once 'includes/template_parts.php';
$user = get_user();

// --- Data for BOTH tabs ---
// 1. Fetch commands for this chef's section awaiting approval
$stmt = $conn->prepare("SELECT * FROM commands WHERE type = ? AND status = 'PendingApproval' ORDER BY created_at ASC");
$stmt->bind_param("s", $user['section']);
$stmt->execute();
$my_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// 2. Fetch all finished commands for history
$history_commands = $conn->query("SELECT * FROM commands WHERE status IN ('Completed', 'Declined', 'Archived') ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<div class="space-y-6">
    <h2 class="text-3xl font-bold text-gray-800">Chef Dashboard - Section <?= e($user['section']) ?></h2>
    
    <!-- TABS -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="#" id="tab-approval" class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-brick-red text-brick-red">Pending Approval</a>
            <a href="#" id="tab-history" class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Command History</a>
        </nav>
    </div>

    <!-- Pending Approval Content -->
    <div id="content-approval" class="tab-content">
        <p class="text-lg text-gray-600 pt-4">You have <?= count($my_commands) ?> new command(s) awaiting approval.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">
            <?php if (count($my_commands) > 0): ?>
                <?php foreach ($my_commands as $command):
                    //
                    // ** THIS IS THE CRITICAL FIX **
                    // The ... has been replaced with the full, working buttons.
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
                <p class="text-gray-500 col-span-full text-center py-10">No new commands to review.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- History Content -->
    <div id="content-history" class="tab-content hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
             <?php if (count($history_commands) > 0): ?>
                <?php foreach ($history_commands as $command): echo render_command_card($command, $user, ''); endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 col-span-full text-center py-10">No command history found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Decline Reason Modal (Unchanged) -->
<div id="decline-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="flex justify-between items-center p-4 border-b">
            <h2 id="decline-modal-title" class="text-xl font-bold text-gray-800">Decline Command</h2>
            <button class="close-decline-modal text-gray-500 hover:text-gray-800"><?= icon_x() ?></button>
        </div>
        <div class="p-6">
            <form action="actions/command_action.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="decline">
                <input type="hidden" name="command_id" id="decline-command-id" value="">
                <div>
                    <label for="declineReason" class="block text-sm font-medium text-gray-700">Reason for Declining</label>
                    <textarea id="declineReason" name="decline_reason" required rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-brick-red focus:border-brick-red" placeholder="e.g., Wrong specs, exceeds machine limits"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" class="close-decline-modal px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-danger-red text-white rounded-md hover:bg-red-800">Decline Command</button>
                </div>
            </form>
        </div>
    </div>
</div>