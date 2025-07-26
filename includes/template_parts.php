<?php
// This file ONLY contains functions for rendering large HTML components.
// All smaller helper functions (like icons) now live in functions.php


function icon_spinner($class = 'w-5 h-5') {
    return '<svg class="animate-spin '.$class.'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
}

function render_progress_bar($command) {
    // This function requires the WORKFLOWS constant, which is defined in functions.php
    $workflow = WORKFLOWS[$command['type']] ?? [];
    if (empty($workflow)) return ''; // Fail gracefully if workflow not found
    
    $total_steps = count($workflow);
    $completed_steps = (int)$command['history_count'];
    $progressPercentage = ($total_steps > 0) ? round(($completed_steps / $total_steps) * 100) : 0;
    
    $status_text = 'Waiting for: ' . e($command['current_step']);
    if ($command['status'] === 'Completed') $status_text = 'Finished';
    if ($command['status'] === 'Declined') $status_text = 'Declined';
    
    $step_text = e($completed_steps) . ' of ' . e($total_steps) . ' complete';

    ob_start();
    ?>
    <div>
        <div class="flex justify-between mb-1">
            <span class="text-sm font-medium text-gray-700"><?= $status_text ?></span>
            <span class="text-sm font-medium text-gray-700"><?= $step_text ?></span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-success-green h-2.5 rounded-full" style="width: <?= $progressPercentage ?>%"></div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function render_command_card($command, $user, $actions_html = '') {
    $status_colors = [
        'Completed' => 'bg-success-green text-white', 'InProgress' => 'bg-blue-500 text-white',
        'PendingApproval' => 'bg-yellow-500 text-white', 'Paused' => 'bg-paused-yellow text-gray-800',
        'Declined' => 'bg-danger-red text-white', 'Archived' => 'bg-gray-500 text-white',
    ];
    $status_color = $status_colors[$command['status']] ?? 'bg-gray-600 text-white';
    $is_admin_or_commercial = in_array($user['role'], ['Admin', 'Commercial']);

    ob_start();
    ?>
    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
      <div class="p-5 flex-grow">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4">
          <h3 class="text-xl font-bold text-brick-red mb-2 sm:mb-0">Command ID: <?= e($command['command_uid']) ?></h3>
          <span class="px-3 py-1 text-sm font-semibold rounded-full <?= $status_color ?>"><?= e($command['status']) ?></span>
        </div>
        <?php if ($command['status'] === 'Declined' && !empty($command['decline_reason'])): ?>
        <div class="bg-red-100 border-l-4 border-danger-red text-red-700 p-3 rounded mb-4">
          <p class="font-bold">Reason for Decline:</p><p><?= e($command['decline_reason']) ?></p>
        </div>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-gray-700">
          <div><span class="font-semibold">Type:</span> BREMAC <?= e($command['type']) ?></div>
          <div><span class="font-semibold">Quantity:</span> <?= e($command['quantity'] ? number_format($command['quantity']) . ' bricks' : 'N/A') ?></div>
          <div><span class="font-semibold">Dimensions:</span> <?= e(!empty($command['dimensions']) ? $command['dimensions'] : 'N/A') ?></div>
          <div><span class="font-semibold">Delivery Date:</span> <?= e(date("d M Y", strtotime($command['delivery_date']))) ?></div>
        </div>
        <?php if ($is_admin_or_commercial): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-gray-700 border-t pt-4 mt-4 border-dashed">
            <div><span class="font-semibold">Client Name:</span> <?= e($command['client_name']) ?></div>
            <div><span class="font-semibold">Client Phone:</span> <?= e($command['client_phone']) ?></div>
        </div>
        <?php endif; ?>
        <?php if (!empty($command['additional_notes'])): ?>
        <div class="mt-4 p-3 bg-gray-50 rounded">
          <p class="font-semibold text-gray-800">Additional Notes:</p>
          <p class="text-gray-600 break-words"><?= e($command['additional_notes']) ?></p>
        </div>
        <?php endif; ?>
        <?php if ($is_admin_or_commercial && in_array($command['status'], ['InProgress', 'Paused', 'Completed'])): ?>
        <div class="mt-4"><?= render_progress_bar($command) ?></div>
        <?php endif; ?>
      </div>
      <?php if (!empty($actions_html)): ?>
      <div class="bg-gray-50 p-4 flex flex-col sm:flex-row items-center justify-end space-y-2 sm:space-y-0 sm:space-x-3">
          <?= $actions_html ?>
      </div>
      <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}



function render_history_drawer($command, $user, $display_status) {
    $is_admin_or_commercial = in_array($user['role'], ['Admin', 'Commercial']);
    
    // New, simpler status colors
    $status_colors = [
        'Completed' => 'bg-green-100 text-green-800',
        'Declined' => 'bg-red-100 text-red-800',
    ];
    // Use the display_status to get the color, fallback to gray
    $status_class = $status_colors[$display_status] ?? 'bg-gray-100 text-gray-800';
    
    ob_start();
    ?>
    <div class="bg-white rounded-lg shadow-sm">
        <!-- This is the clickable part of the drawer -->
        <button class="history-toggle w-full p-4 text-left flex justify-between items-center" data-target="history-content-<?= e($command['id']) ?>">
            <div class="flex-grow pr-4">
                <p class="font-bold text-gray-800 truncate"><?= e($command['command_uid']) ?></p>
                <?php if ($is_admin_or_commercial): ?>
                    <p class="text-sm text-gray-500 truncate"><?= e($command['client_name']) ?> - <?= e($command['client_phone']) ?></p>
                <?php else: ?>
                    <p class="text-sm text-gray-500">Type: BREMAC <?= e($command['type']) ?></p>
                <?php endif; ?>
            </div>
            <div class="flex items-center space-x-4 flex-shrink-0">
                <!-- This now correctly shows "Completed" or "Declined" instead of "Archived" -->
                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $status_class ?>"><?= e($display_status) ?></span>
                <span class="text-gray-400 chevron-icon transform transition-transform"><?= icon_chevron_right('w-5 h-5') ?></span>
            </div>
        </button>

        <!-- This is the hidden content that expands -->
        <div id="history-content-<?= e($command['id']) ?>" class="hidden p-4 border-t border-gray-200">
            <div class="text-sm text-gray-700 space-y-2">
                <p><span class="text-gray-500">Type:</span> <span class="font-semibold"><?= 'BREMAC ' . e($command['type']) ?></span></p>
                <p><span class="text-gray-500">Quantity:</span> <span class="font-semibold"><?= e($command['quantity'] ? number_format($command['quantity']) . ' bricks' : 'N/A') ?></span></p>
                <p><span class="text-gray-500">Dimensions:</span> <span class="font-semibold"><?= e(!empty($command['dimensions']) ? $command['dimensions'] : 'N/A') ?></span></p>
                <p><span class="text-gray-500">Delivery Date:</span> <span class="font-semibold"><?= e(date("d M Y", strtotime($command['delivery_date']))) ?></span></p>
                <?php if ($is_admin_or_commercial): ?>
                    <p><span class="text-gray-500">Client Name:</span> <span class="font-semibold"><?= e($command['client_name']) ?></span></p>
                    <p><span class="text-gray-500">Client Phone:</span> <span class="font-semibold"><?= e($command['client_phone']) ?></span></p>
                <?php endif; ?>
                <?php if (!empty($command['additional_notes'])): ?>
                    <div class="pt-2"><p class="font-semibold text-gray-800">Additional Notes:</p><p class="text-gray-600 break-words pt-1"><?= e($command['additional_notes']) ?></p></div>
                <?php endif; ?>
                <?php if ($command['status'] === 'Declined' && !empty($command['decline_reason'])): ?>
                    <div class="pt-2"><p class="font-semibold text-red-800">Decline Reason:</p><p class="text-red-600 break-words pt-1"><?= e($command['decline_reason']) ?></p></div>
                <?php endif; ?>
            </div>
            
            <div class="text-right mt-4">
                <!-- ** THIS IS THE NEW DELETE BUTTON ** -->
                <!-- It uses classes and data-attributes to work with our new custom modal -->
                <button class="open-delete-one-modal-btn text-xs font-semibold text-red-600 hover:text-red-800" data-command-id="<?= e($command['id']) ?>" data-command-uid="<?= e($command['command_uid']) ?>">
                    Delete
                </button>
            </div>
            
        </div>
    </div>
    <?php
    return ob_get_clean();
}