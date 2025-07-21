<?php
// includes/footer.php (Definitive Version)
?>
            </div> <!-- This closes the .container div from header.php -->
        </main> <!-- This closes the main tag from header.php -->

        <!-- This is the professional footer, now made smaller -->
        <footer class="bg-black text-gray-400 mt-auto pt-6 text-sm">
            <div class="container mx-auto px-6 lg:px-8">
                
                <!-- Contact Information Grid (smaller gaps and text) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center md:text-left">
                    <div class="space-y-1">
                        <h3 class="font-bold text-base text-white">BRIMAK</h3>
                        <p class="text-xs">km.3,5 route Dar sidi Aissa<br>b.p.338 Safi - 46000 Safi (Maroc).</p>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-base text-white">Contact</h3>
                        <p class="text-xs"><a href="mailto:brimak.maroc@gmail.com" class="hover:text-white">brimak.maroc@gmail.com</a></p>
                        <p class="text-xs">Tel: 05 24 62 37 75 / 62 60 92</p>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-base text-white">Fax</h3>
                        <p class="text-xs">05 24 62 97 91 / 61 25 11</p>
                    </div>
                </div>

                <!-- Divider Line (smaller margin) -->
                <div class="my-6"><hr class="border-gray-700"></div>

                <!-- Copyright & Developer Credit (smaller text and padding) -->
                <div class="flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 pb-6">
                    <p>© <?= date('Y') ?> BRIMAK. All Rights Reserved.</p>
                    <p class="mt-2 md:mt-0">
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