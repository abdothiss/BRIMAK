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

    <script src="assets/js/main.js"></script>
</body>
</html>
<?php
// Close the database connection at the very end.
if (isset($conn)) {
    $conn->close();
}
?>