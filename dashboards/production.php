<?php
// Included from index.php
require_once 'includes/template_parts.php';
$user = get_user();

// --- Data for BOTH tabs ---
// 1. Fetch active tasks for this worker
$stmt = $conn->prepare("SELECT * FROM commands WHERE status = 'InProgress' AND current_step = ? AND type = ? ORDER BY created_at ASC");
$stmt->bind_param("ss", $user['role'], $user['section']);
$stmt->execute();
$my_commands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// 2. Fetch all finished commands for history
$history_commands = $conn->query("SELECT * FROM commands WHERE status IN ('Completed', 'Declined', 'Archived') ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<div class="space-y-6">
    <h2 class="text-3xl font-bold text-gray-800"><?= e($user['role']) ?> Dashboard</h2>

    <!-- TABS -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="#" id="tab-tasks" class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-brick-red text-brick-red">Current Tasks</a>
            <a href="#" id="tab-history" class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">Command History</a>
        </nav>
    </div>

    <!-- Current Tasks Content -->
    <div id="content-tasks" class="tab-content">
        <p class="text-lg text-gray-600 pt-4">You have <?= count($my_commands) ?> task(s) to complete.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">
            <?php if (count($my_commands) > 0): ?>
                <?php foreach ($my_commands as $command):
                    //
                    // ** THIS IS THE CRITICAL FIX **
                    // The ... has been replaced with the full, working form.
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
                <div class="col-span-full text-center bg-white p-10 rounded-lg shadow-md">
                    <p class="text-gray-500 text-xl">No pending tasks at the moment. Great job!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- History Content (Unchanged) -->
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