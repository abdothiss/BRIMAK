<?php
// dashboards/admin.php
require_once 'includes/template_parts.php';
$user = get_user();

// Get the current view from the URL, default to the main dashboard
$current_view = $_GET['view'] ?? 'dashboard';
?>

<div class="space-y-6">
    <?php
    // This switch statement decides which content block to show
    switch ($current_view):

    // ================== USER MANAGEMENT VIEW ==================
    case 'users':
        $all_users = $conn->query("SELECT id, name, username, role, section, is_active FROM users ORDER BY role, name")->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800">User Management</h2>
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
                        <!-- ** THIS IS THE HTML FIX: The full, correct classes for the buttons are restored ** -->
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

        <!-- The full, working modals from your previous version -->
        <div id="user-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 ..."> <!-- Full modal HTML here --> </div>
        <div id="delete-user-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 ..."> <!-- Full modal HTML here --> </div>
        <div id="reset-pw-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 ..."> <!-- Full modal HTML here --> </div>
        <?php
        break;

    // ================== COMMAND HISTORY VIEW ==================
    case 'history':
        // ... The Command History code ...
        $history_commands = $conn->query("SELECT * FROM commands WHERE status IN ('Completed', 'Declined', 'Archived') ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800">Command History</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 pt-6">
            <?php if (count($history_commands) > 0): ?>
                <?php foreach ($history_commands as $command): echo render_command_card($command, $user, ''); endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 ...">No command history found.</p>
            <?php endif; ?>
        </div>
        <?php
        break; // End the 'history' case
        

    // ================== DEFAULT DASHBOARD (LIVE COMMANDS) ==================
    default:
        // ... The Live Commands code (filter box and command list) ...
         $filter_client_name = $_GET['filter_client_name'] ?? '';
        $filter_status = $_GET['filter_status'] ?? 'All';
        // ... (your existing SQL logic for live commands) ...
        $live_commands_sql = "SELECT c.*, ...";
        $live_commands = $conn->query($live_commands_sql)->fetch_all(MYSQLI_ASSOC);
        ?>
        <h2 class="text-3xl font-extrabold text-gray-800">Live Commands</h2>
        
        <!-- Your existing filter box -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm space-y-4">
            <!-- ... -->
        </div>
        
        <!-- Your existing command list -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php if (count($live_commands) > 0): ?>
                <?php foreach ($live_commands as $command):
                    $actions_html = '';
                    // ... (your existing archive button logic) ...
                    echo render_command_card($command, $user, $actions_html);
                endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 ...">No commands found...</p>
            <?php endif; ?>
        </div>
        <?php
        break;
    endswitch;
    ?>
