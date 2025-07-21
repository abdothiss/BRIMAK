<?php // dashboards/admin_views/_history.php
$history_commands = $conn->query("SELECT * FROM commands WHERE status IN ('Completed', 'Declined', 'Archived') ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<h2 class="text-3xl font-extrabold text-gray-800">Command History</h2>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 pt-6">
    <?php if (count($history_commands) > 0): ?>
        <?php foreach ($history_commands as $command): echo render_command_card($command, $user, ''); endforeach; ?>
    <?php else: ?>
        <p class="col-span-full ...">No command history found.</p>
    <?php endif; ?>
</div>