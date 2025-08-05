<?php
// includes/footer.php (Definitive, Unabbreviated Version)
?>
            </div> <!-- Closes the .container div from header.php -->
        </main> <!-- Closes the main tag from header.php -->

        <footer class="bg-black text-gray-400 mt-auto pt-3 pb-2 text-xs">
            <div class="container mx-auto px-3 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-center md:text-left">
                    <div class="space-y-0.5 md:space-y-1">
                        <h3 class="font-bold text-xs text-white"><?= t('footer_address_title') ?></h3>
                        <p class="text-[11px] leading-tight">km.3,5 route Dar sidi Aissa<br>b.p.338 Safi - 46000 Safi (Maroc).</p>
                    </div>
                    <div class="space-y-0.5 md:space-y-1">
                        <h3 class="font-bold text-xs text-white"><?= t('footer_contact_title') ?></h3>
                        <p class="text-[11px] leading-tight"><a href="mailto:brimak.maroc@gmail.com" class="hover:text-white">brimak.maroc@gmail.com</a></p>
                        <p class="text-[11px] leading-tight">Tel: 05 24 62 37 75 / 62 60 92</p>
                    </div>
                    <div class="space-y-0.5 md:space-y-1">
                        <h3 class="font-bold text-xs text-white"><?= t('footer_fax_title') ?></h3>
                        <p class="text-[11px] leading-tight">05 24 62 97 91 / 61 25 11</p>
                    </div>
                </div>

                <div class="my-3 md:my-6"><hr class="border-gray-700"></div>

                <div class="flex flex-col md:flex-row justify-between items-center text-[10px] text-gray-500 pb-1 md:pb-6">
    
                    <!-- This part was missing -->
                    <p>© <?= date('Y') ?> BRIMAK. <?= t('footer_rights_reserved') ?>.</p>
                    
                    <!-- This is the corrected language switcher -->
                    <div class="flex items-center space-x-4 my-4 md:my-0">
                        <?php
                        // Get the current view, default to 'dashboard' if not set.
                        $current_view_for_lang = $_GET['view'] ?? 'dashboard';
                        ?>
                        <a href="?view=<?= e($current_view_for_lang) ?>&lang=en" class="hover:text-white <?= ($lang_code === 'en' ? 'font-bold text-white' : '') ?>">English</a>
                        <span class="text-gray-600">|</span>
                        <a href="?view=<?= e($current_view_for_lang) ?>&lang=fr" class="hover:text-white <?= ($lang_code === 'fr' ? 'font-bold text-white' : '') ?>">Français</a>
                    </div>

                    <!-- This part was also missing -->
                    <p class="mt-1 md:mt-0"><?= t('footer_developed_by') ?> <a href="https://www.instagram.com/slx7z/" target="_blank" rel="noopener noreferrer" class="hover:text-white font-semibold">@slx7z</a></p>

                </div>
            </div>
        </footer>
    </div> <!-- This closes the min-h-screen wrapper from header.php -->

   <!-- Custom Confirmation Modals for History Deletion -->
    <div id="delete-one-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4"><?= icon_trash('w-8 h-8 text-red-500') ?></div>
                <h3 class="text-lg font-bold text-gray-800"><?= t('modal_delete_command_title') ?></h3>
                <p class="text-sm text-gray-500 my-2"><?= t('modal_delete_command_confirm') ?> <strong id="delete-one-uid" class="font-bold"></strong>? <?= t('modal_undone_warning') ?></p>
            </div>
            <form action="actions/command_action.php" method="POST" class="p-4 bg-gray-50 rounded-b-lg flex justify-center gap-4">
                <input type="hidden" name="action" value="delete_history"><input type="hidden" name="view" value="history"><input type="hidden" name="command_id" id="delete-one-id" value="">
                <button type="button" class="close-history-modal-btn px-6 py-2 bg-gray-200 text-gray-800 rounded-md font-semibold hover:bg-gray-300"><?= t('modal_button_no') ?></button>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md font-semibold hover:bg-red-700"><?= t('modal_button_yes_delete') ?></button>
            </form>
        </div>
    </div>
    <div id="delete-all-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4"><?= icon_trash('w-8 h-8 text-red-500') ?></div>
                <h3 class="text-lg font-bold text-gray-800"><?= t('modal_delete_all_title') ?></h3>
                <p class="text-sm text-gray-500 my-2"><?= t('modal_delete_all_confirm') ?></p>
            </div>
            <form action="actions/command_action.php" method="POST" class="p-4 bg-gray-50 rounded-b-lg flex justify-center gap-4">
                <input type="hidden" name="action" value="delete_all_history"><input type="hidden" name="view" value="history">
                <button type="button" class="close-history-modal-btn px-6 py-2 bg-gray-200 text-gray-800 rounded-md font-semibold hover:bg-gray-300"><?= t('modal_button_no') ?></button>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md font-semibold hover:bg-red-700"><?= t('modal_button_yes_delete_all') ?></button>
            </form>
        </div>
    </div>

     <!-- =================================================================== -->
   <!-- ALL MODALS FOR THE ENTIRE APPLICATION NOW LIVE HERE -->
   <!-- =================================================================== -->

    <!-- ** NEW: Modal for Cancelling a Command ** -->
    <div id="cancel-command-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <?= icon_trash('w-8 h-8 text-red-500') ?>
                </div>
                <h3 class="text-lg font-bold text-gray-800"><?= t('modal_cancel_command_title') ?></h3>
                <p class="text-sm text-gray-500 my-2">
                    <?= t('modal_cancel_command_text') ?> <strong id="cancel-command-uid" class="font-bold"></strong>? <?= t('modal_undone_warning') ?>
                </p>
            </div>
            <form action="actions/command_action.php" method="POST" class="p-4 bg-gray-50 rounded-b-lg flex justify-center gap-4">
                <input type="hidden" name="action" value="cancel_command">
                <input type="hidden" name="view" value="dashboard">
                <input type="hidden" name="command_id" id="cancel-command-id" value="">
                <button type="button" class="close-modal-btn px-6 py-2 bg-gray-200 text-gray-800 rounded-md font-semibold hover:bg-gray-300"><?= t('modal_button_no_keep') ?></button>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md font-semibold hover:bg-red-700"><?= t('modal_button_yes_cancel') ?></button>
            </form>
        </div>
    </div>

    <!-- ** NEW: Modal for Viewing Command Progress ** -->
    <div id="view-progress-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm overflow-hidden">
            
            <!-- THIS IS THE REDESIGNED HEADER -->
            <div class="relative bg-brick-red p-4">
                <h2 class="text-xl font-bold text-white text-center">Command Progress</h2>
                <!-- This button has the correct class to be found by the existing universal JS -->
                <button class="close-modal-btn absolute top-0 right-0 mt-3 mr-3 text-white/70 hover:text-white text-2xl">×</button>
            </div>

            <div id="progress-steps-container" class="p-6 space-y-4">
                <!-- JavaScript will build the steps here -->
            </div>
        </div>
    </div>

    <div class="hidden" id="icon-templates">
        <div id="template-icon-check"><?= icon_check_circle('w-6 h-6 text-green-600') ?></div>
        <div id="template-icon-clock"><?= icon_clock('w-6 h-6 text-yellow-500') ?></div>
    </div>
    
    
    

    <script src="assets/js/main.js"></script>

    <?php
    // Get the current view to decide if we should show the button
    $current_view_for_scroll = $_GET['view'] ?? 'dashboard';
    $excluded_views = ['settings', 'profile']; // Add any other short pages here

    if (!in_array($current_view_for_scroll, $excluded_views)):
    ?>
        <button id="scroll-to-top-btn" class="fixed bottom-5 right-5 z-50 p-3 bg-brick-red text-white rounded-full shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 ease-in-out hover:bg-red-800">
            <?= icon_arrow_up('w-6 h-6') ?>
        </button>
    <?php endif; ?>
    
</body>
</html>
<?php
if (isset($conn)) {
    $conn->close();
}
?>