<?php // dashboards/admin_views/_live_commands.php
$filter_client_name = $_GET['filter_client_name'] ?? '';
$filter_status = $_GET['filter_status'] ?? 'All';
$where_conditions = ["c.status != 'Archived'"];
if (!empty($filter_client_name)) { $where_conditions[] = "c.client_name LIKE '%" . $conn->real_escape_string($filter_client_name) . "%'"; }
if ($filter_status !== 'All' && in_array($filter_status, ALL_STATUSES)) { $where_conditions[] = "c.status = '" . $conn->real_escape_string($filter_status) . "'"; }
$sql_where = "WHERE " . implode(' AND ', $where_conditions);
$live_commands_sql = "SELECT c.*, (SELECT COUNT(*) FROM command_history ch WHERE ch.command_id = c.id) as history_count FROM commands c $sql_where ORDER BY c.created_at DESC";
$live_commands = $conn->query($live_commands_sql)->fetch_all(MYSQLI_ASSOC);
?>
<h2 class="text-3xl font-extrabold text-gray-800">Live Commands</h2>
<div class="mb-6 bg-white p-4 rounded-lg shadow-sm space-y-4">
    <!-- Filter Form -->
</div>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php if (count($live_commands) > 0): ?>
        <?php foreach ($live_commands as $command):
            $actions_html = '';
            if ($command['status'] === 'Completed' || $command['status'] === 'Declined') {
                $actions_html .= '<form action="actions/command_action.php" method="POST" ...>...</form>';
            }
            echo render_command_card($command, $user, $actions_html);
        endforeach; ?>
    <?php else: ?>
        <p class="col-span-full ...">No commands found...</p>
    <?php endif; ?>
</div>