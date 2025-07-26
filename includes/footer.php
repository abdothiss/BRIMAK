<?php
// includes/footer.php (Condensed Mobile Version)
?>
            </div> <!-- This closes the .container div from header.php -->
        </main> <!-- This closes the main tag from header.php -->

        <!-- Condensed, mobile-friendly footer -->
        <footer class="bg-black text-gray-400 mt-auto pt-3 pb-2 text-xs">
            <div class="container mx-auto px-3 lg:px-8">

                <!-- Contact Information Grid (tighter gaps, smaller text) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-center md:text-left">
                    <div class="space-y-0.5 md:space-y-1">
                        <h3 class="font-bold text-xs text-white">BRIMAK</h3>
                        <p class="text-[11px] leading-tight">
                            km.3,5 route Dar sidi Aissa<br>
                            b.p.338 Safi - 46000 Safi (Maroc).
                        </p>
                    </div>
                    <div class="space-y-0.5 md:space-y-1">
                        <h3 class="font-bold text-xs text-white">Contact</h3>
                        <p class="text-[11px] leading-tight">
                            <a href="mailto:brimak.maroc@gmail.com" class="hover:text-white">brimak.maroc@gmail.com</a>
                        </p>
                        <p class="text-[11px] leading-tight">Tel: 05 24 62 37 75 / 62 60 92</p>
                    </div>
                    <div class="space-y-0.5 md:space-y-1">
                        <h3 class="font-bold text-xs text-white">Fax</h3>
                        <p class="text-[11px] leading-tight">05 24 62 97 91 / 61 25 11</p>
                    </div>
                </div>

                <!-- Divider Line (less margin on mobile) -->
                <div class="my-3 md:my-6"><hr class="border-gray-700"></div>

                <!-- Copyright & Developer Credit (smaller text, less padding) -->
                <div class="flex flex-col md:flex-row justify-between items-center text-[10px] text-gray-500 pb-1 md:pb-6">
                    <p>© <?= date('Y') ?> BRIMAK. All Rights Reserved.</p>
                    <p class="mt-1 md:mt-0">
                        Developed by 
                        <a href="https://www.instagram.com/slx7z/" target="_blank" rel="noopener noreferrer" class="hover:text-white font-semibold">@slx7z</a>
                    </p>
                </div>

            </div>
        </footer>
    </div> <!-- This closes the min-h-screen wrapper from header.php -->

   <!-- ============================================= -->
<!-- NEW: Custom Confirmation Modals for History Deletion -->
<!-- ============================================= -->
<div id="delete-one-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <?= icon_trash('w-8 h-8 text-red-500') ?>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Delete Command</h3>
            <p class="text-sm text-gray-500 my-2">Are you sure you want to permanently delete command <strong id="delete-one-uid" class="font-bold"></strong>? This cannot be undone.</p>
        </div>
        <form action="actions/command_action.php" method="POST" class="p-4 bg-gray-50 rounded-b-lg flex justify-center gap-4">
            <input type="hidden" name="action" value="delete_history">
            <input type="hidden" name="view" value="history"> <!-- To redirect back correctly -->
            <input type="hidden" name="command_id" id="delete-one-id" value="">
            <button type="button" class="close-history-modal-btn px-6 py-2 bg-gray-200 text-gray-800 rounded-md font-semibold hover:bg-gray-300">No, keep it</button>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md font-semibold hover:bg-red-700">Yes, delete it</button>
        </form>
    </div>
</div>

<div id="delete-all-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <?= icon_trash('w-8 h-8 text-red-500') ?>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Delete All History</h3>
            <p class="text-sm text-gray-500 my-2">Are you sure you want to PERMANENTLY delete ALL command history? This action cannot be undone.</p>
        </div>
        <form action="actions/command_action.php" method="POST" class="p-4 bg-gray-50 rounded-b-lg flex justify-center gap-4">
            <input type="hidden" name="action" value="delete_all_history">
            <input type="hidden" name="view" value="history"> <!-- To redirect back correctly -->
            <button type="button" class="close-history-modal-btn px-6 py-2 bg-gray-200 text-gray-800 rounded-md font-semibold hover:bg-gray-300">No, keep it</button>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md font-semibold hover:bg-red-700">Yes, delete all</button>
        </form>
    </div>
</div>


    <script src="assets/js/main.js"></script>
</body>
</html>
<?php
// Close the database connection at the very end.
if (isset($conn)) {
    $conn->close();
}
?>