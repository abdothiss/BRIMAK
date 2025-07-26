<?php
require_once 'includes/template_parts.php';
$user = get_user();

// 1. Live Commands (with filters)
$filter_status = $_GET['filter'] ?? 'All';
$sql_where = ($filter_status !== 'All' && in_array($filter_status, ALL_STATUSES)) ? "WHERE c.status = '" . $conn->real_escape_string($filter_status) . "'" : "";
$commands_sql = "SELECT c.*, (SELECT COUNT(*) FROM command_history ch WHERE ch.command_id = c.id) as history_count FROM commands c $sql_where ORDER BY c.created_at DESC";
$my_commands = $conn->query($commands_sql)->fetch_all(MYSQLI_ASSOC);

// 2. Command History
$history_commands = $conn->query("SELECT * FROM commands WHERE status IN ('Completed', 'Declined') ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Commercial Dashboard</h2>
        <button id="open-create-modal-btn" class="w-full md:w-auto flex items-center justify-center space-x-2 px-6 py-3 bg-brick-red text-white font-semibold rounded-lg shadow-md hover:bg-red-800 transition-colors">
            <?= icon_plus('w-6 h-6') ?>
            <span>Create New Command</span>
        </button>
    </div>
    
    <!-- TABS -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="#" id="tab-live" class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-brick-red text-brick-red">Live Commands</a>
            <a href="#" id="tab-history" class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500">Command History</a>
        </nav>
    </div>

    <!-- Live Command List -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php if (count($my_commands) > 0): ?>
            <?php foreach ($my_commands as $command):
                $actions_html = '';
                if ($command['status'] === 'Declined') {
                    // Note the data-command attribute. JS will use this.
                    $actions_html .= '<button class="edit-command-btn flex items-center space-x-2 px-4 py-2 text-sm font-medium rounded-md bg-blue-100 text-blue-800 hover:bg-blue-200" data-command=\''.json_encode($command).'\'>
                                        '.icon_edit('w-4 h-4').'<span>Modify & Resend</span>
                                      </button>';
                }
                
                if ($command['status'] === 'Completed' || $command['status'] === 'Declined') {
                    $actions_html .= '<form action="actions/command_action.php" method="POST" class="inline-block" title="Remove from live view">
                                        <input type="hidden" name="command_id" value="'.e($command['id']).'">
                                        <button type="submit" name="action" value="archive" class="p-2 text-gray-400 hover:text-gray-700">
                                            '.icon_x('w-5 h-5').'
                                        </button>
                                      </form>';
                }
                echo render_command_card($command, $user, $actions_html);
            endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 col-span-full text-center py-10">No commands match the current filter.</p>
        <?php endif; ?>
    </div>
</div>
             <!-- History Content -->

<div id="content-history" class="tab-content hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php if (count($history_commands) > 0): ?>
                <?php foreach ($history_commands as $command): echo render_command_card($command, $user, ''); endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 col-span-full text-center py-10">No command history.</p>
            <?php endif; ?>
        </div>
    </div>
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