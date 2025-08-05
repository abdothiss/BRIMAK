<?php
// includes/template_parts.php (Definitive, Unabbreviated, Translated Version)

function render_progress_bar($command) {
    $workflow = WORKFLOWS[$command['type']] ?? [];
    if (empty($workflow)) return '';
    
    $total_steps = count($workflow);
    $completed_steps = (int)$command['history_count'];
    $progressPercentage = ($total_steps > 0) ? round(($completed_steps / $total_steps) * 100) : 0;
    
    $status_text = t('progress_waiting_for') . ': ' . t('role_' . strtolower(e($command['current_step'])));
    if ($command['status'] === 'Completed') $status_text = t('progress_finished');
    if ($command['status'] === 'Declined') $status_text = t('progress_declined');
    
    $step_text = e($completed_steps) . ' ' . t('progress_of_steps') . ' ' . e($total_steps) . ' ' . t('progress_steps_complete');

    ob_start();
    ?>
    <div>
        <div class="flex justify-between mb-1"><span class="text-sm font-medium text-gray-700"><?= $status_text ?></span><span class="text-sm font-medium text-gray-700"><?= $step_text ?></span></div>
        <div class="w-full bg-gray-200 rounded-full h-2.5"><div class="bg-success-green h-2.5 rounded-full" style="width: <?= $progressPercentage ?>%"></div></div>
    </div>
    <?php
    return ob_get_clean();
}




function render_command_card($command, $user, $actions_html = '') {
    // Status color logic (unchanged)
    $status_colors = [ 'Completed' => 'bg-success-green text-white', 'InProgress' => 'bg-blue-500 text-white', 'PendingApproval' => 'bg-yellow-500 text-white', 'Declined' => 'bg-danger-red text-white', 'Cancelled' => 'bg-gray-500 text-white' ];
    $status_color = $status_colors[$command['status']] ?? 'bg-gray-600 text-white';
    $is_admin_or_commercial = in_array($user['role'], ['Admin', 'Commercial']);

    // Progress modal data logic (unchanged)
    $workflow_json = json_encode(WORKFLOWS[$command['type']] ?? []);
    $completed_steps_array = isset($command['completed_steps']) && $command['completed_steps'] ? explode(',', $command['completed_steps']) : [];
    $completed_steps_json = json_encode($completed_steps_array);
    
    // Translated workflow data for modal display
    $workflow_steps = WORKFLOWS[$command['type']] ?? [];
    $translated_workflow = array_map(function($step) {
        return t('role_' . strtolower($step));
    }, $workflow_steps);
    $translated_workflow_json = json_encode($translated_workflow);


    ob_start();
    ?>
    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
      <div class="p-5 flex-grow">
        <!-- CARD HEADER -->
        <div class="flex justify-between items-start mb-4">
          <div class="flex-grow pr-4">
            <h3 class="text-xl font-bold text-brick-red mb-1 sm:mb-0"><?= t('command_id') ?>: <?= e($command['command_uid']) ?></h3>
            <span class="px-3 py-1 text-sm font-semibold rounded-full <?= $status_color ?>"><?= t('status_'.strtolower($command['status'])) ?></span>
          </div>
          
          <!-- THREE DOTS MENU -->
          <?php if ($is_admin_or_commercial && in_array($command['status'], ['PendingApproval', 'InProgress', 'Paused'])): ?>
          <div class="relative command-menu-container flex-shrink-0">
            <button class="command-menu-btn p-1 text-gray-500 hover:text-gray-800 rounded-full hover:bg-gray-100">
              <?= icon_dots_vertical('w-5 h-5') ?>
            </button>
            <div class="command-menu-panel absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden border">
              <!-- TRANSLATED "View Progress" Button -->
              <button class="open-progress-modal-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-workflow='<?= e($workflow_json) ?>' data-completed='<?= e($completed_steps_json) ?>' data-translated-workflow='<?= e($translated_workflow_json) ?>' data-command-uid="<?= e($command['command_uid']) ?>">
                <?= t('menu_view_progress') ?>
              </button>
              <!-- TRANSLATED "Cancel Command" Button -->
              <button class="open-cancel-modal-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50" data-command-id="<?= e($command['id']) ?>" data-command-uid="<?= e($command['command_uid']) ?>">
                <?= t('menu_cancel_command') ?>
              </button>
            </div>
          </div>
          <?php endif; ?>
        </div>

        
        <?php if ($command['status'] === 'Declined' && !empty($command['decline_reason'])): ?>
        <div class="bg-red-100 border-l-4 border-danger-red text-red-700 p-3 rounded mb-4"><p class="font-bold"><?= t('reason_for_decline') ?>:</p><p><?= e($command['decline_reason']) ?></p></div>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-gray-700">
          <div><span class="font-semibold"><?= t('commercial_modal_product_label') ?>:</span> <?= e($command['product_name'] ?? 'N/A') ?></div>
          <div><span class="font-semibold"><?= t('type') ?>:</span> BRIMAK <?= e($command['type']) ?></div>
          <div><span class="font-semibold"><?= t('commercial_modal_arrival_date_label') ?>:</span> <?= e(date("d M Y", strtotime($command['arrival_date']))) ?></div>
          <div><span class="font-semibold"><?= t('commercial_modal_deadline_date_label') ?>:</span> <?= e(date("d M Y", strtotime($command['deadline_date']))) ?></div>
        </div>
        <?php if ($is_admin_or_commercial): ?>
            <div class="border-t pt-4 mt-4 border-dashed">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0 text-sm">
                    
                    <!-- Client Name with Icon -->
                    <div class="flex items-center space-x-2 text-gray-800" title="<?= t('client_name') ?>">
                        <span class="text-gray-500"><?= icon_user('w-4 h-4') ?></span>
                        <span class="font-semibold"><?= e($command['client_name']) ?></span>
                    </div>

                    <!-- Client Phone with Icon -->
                    <div class="flex items-center space-x-2 text-gray-800" title="<?= t('client_phone') ?>">
                        <span class="text-gray-500"><?= icon_phone('w-4 h-4') ?></span>
                        <span class="font-mono font-semibold"><?= e($command['client_phone']) ?></span>
                    </div>

                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($command['additional_notes'])): ?>
        <div class="mt-4 p-3 bg-gray-50 rounded"><p class="font-semibold text-gray-800"><?= t('additional_notes') ?>:</p><p class="text-gray-600 break-words"><?= e($command['additional_notes']) ?></p></div>
        <?php endif; ?>
        <?php if ($is_admin_or_commercial && in_array($command['status'], ['InProgress', 'Paused', 'Completed'])): ?>
        <div class="mt-4"><?= render_progress_bar($command) ?></div>
        <?php endif; ?>
      </div>
      <?php if (!empty($actions_html)): ?>
      <div class="bg-gray-50 p-4 flex justify-end items-center space-x-3"><?= $actions_html ?></div>
      <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}


function render_history_drawer($command, $user, $display_status) {
    $is_admin_or_commercial = in_array($user['role'], ['Admin', 'Commercial']);
    $status_colors = [
        'Completed' => 'bg-green-100 text-green-800 border-green-300',
        'Declined' => 'bg-red-100 text-red-800 border-red-300',
        'Cancelled' => 'bg-gray-200 text-gray-700 border-gray-300'
    ];
    $status_class = $status_colors[$display_status] ?? 'bg-gray-100 text-gray-800';

    ob_start();
    ?>
    <div class="bg-white rounded-lg shadow-sm transition-shadow duration-200 hover:shadow-md">
        <!-- Main Toggle Button (The only button to click) -->
        <button class="history-toggle w-full p-4 text-left flex justify-between items-center" data-target="history-content-<?= e($command['id']) ?>">
            <div class="flex-grow pr-4">
                <p class="font-bold text-gray-800 truncate"><?= e($command['command_uid']) ?></p>
                <p class="text-sm text-gray-500 truncate">
                    <?= e($command['product_name'] ?? 'N/A') ?>
                </p>
            </div>
            <div class="flex items-center space-x-4 flex-shrink-0">
                <span class="inline-block w-24 text-center px-2 py-1 text-xs font-semibold rounded-full <?= $status_class ?>"><?= t('status_'.strtolower($display_status)) ?></span>
                <span class="text-gray-400 chevron-icon transform transition-transform"><?= icon_chevron_right('w-5 h-5') ?></span>
            </div>
        </button>

        <!-- The Drawer Content (All details are visible at once) -->
        <div id="history-content-<?= e($command['id']) ?>" class="hidden">
            <div class="border-t border-gray-200 p-5">
                
                <!-- Main Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm mb-4">
                    <p>
                        <span class="block text-xs text-gray-500 font-medium"><?= t('commercial_modal_product_label') ?></span>
                        <span class="font-semibold text-gray-800"><?= e($command['product_name'] ?? 'N/A') ?></span>
                    </p>
                     <p>
                        <span class="block text-xs text-gray-500 font-medium"><?= t('type') ?></span>
                        <span class="font-semibold text-gray-800"><?= 'BRIMAK ' . e($command['type']) ?></span>
                    </p>
                    <p>
                        <span class="block text-xs text-gray-500 font-medium"><?= t('commercial_modal_arrival_date_label') ?></span> 
                        <span class="font-semibold text-gray-800"><?= e(date("d F Y", strtotime($command['arrival_date']))) ?></span>
                    </p>
                    <p>
                        <span class="block text-xs text-gray-500 font-medium"><?= t('commercial_modal_deadline_date_label') ?></span> 
                        <span class="font-semibold text-gray-800"><?= e(date("d F Y", strtotime($command['deadline_date']))) ?></span>
                    </p>
                </div>

                <!-- Client Info (Only for Admin/Commercial) -->
                <?php if ($is_admin_or_commercial): ?>
                    <div class="border-t pt-4 mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <p>
                            <span class="block text-xs text-gray-500 font-medium"><?= t('client_name') ?></span>
                            <span class="font-semibold text-gray-800"><?= e($command['client_name']) ?></span>
                        </p>
                        <p>
                            <span class="block text-xs text-gray-500 font-medium"><?= t('client_phone') ?></span>
                            <span class="font-semibold text-gray-800 font-mono"><?= e($command['client_phone']) ?></span>
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Notes & Reasons Section -->
                <?php if (!empty($command['additional_notes'])): ?>
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-xs font-semibold text-gray-600 mb-1"><?= t('additional_notes') ?></p>
                        <p class="text-sm text-gray-800 bg-gray-50 p-3 rounded-md"><?= nl2br(e($command['additional_notes'])) ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if ($command['status'] === 'Declined' && !empty($command['decline_reason'])): ?>
                    <div class="mt-4 pt-4 border-t border-red-200/60">
                        <p class="text-xs font-semibold text-red-700 mb-1"><?= t('reason_for_decline') ?></p>
                        <p class="text-sm text-red-900 bg-red-50 p-3 rounded-md"><?= nl2br(e($command['decline_reason'])) ?></p>
                    </div>
                <?php endif; ?>

            </div>
            <!-- Footer with Delete Button -->
            <div class="bg-gray-50/50 px-5 py-3 text-right border-t">
                <button class="open-delete-one-modal-btn text-gray-500 hover:text-red-600 p-1" data-command-id="<?= e($command['id']) ?>" data-command-uid="<?= e($command['command_uid']) ?>" title="<?= t('delete_from_history') ?>">
                    <?= icon_trash('w-5 h-5') ?>
                </button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
