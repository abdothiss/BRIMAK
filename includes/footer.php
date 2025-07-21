<?php
// This is the correct, clean structure for footer.php
?>
        </div> <!-- This closes the div.dashboard from header.php -->
    </main> <!-- This closes the main tag from header.php -->

    <!-- ============================================= -->
    <!-- NEW PROFESSIONAL FOOTER STARTS HERE -->
    <!-- ============================================= -->
    <footer class="bg-black text-gray-300 mt-auto pt-8">
        <div class="container mx-auto px-6 lg:px-8">
            
            <!-- Section 1: Contact Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <!-- Column 1: Company & Address -->
                <div class="space-y-2">
                    <h3 class="font-bold text-lg text-white">BRIMAK</h3>
                    <p class="text-sm">km.3,5 route Dar sidi Aissa<br>b.p.338 Safi - 46000 Safi (Maroc).</p>
                </div>
                <!-- Column 2: Email & Phone -->
                <div class="space-y-2">
                    <h3 class="font-bold text-lg text-white">Contact</h3>
                    <p class="text-sm">
                        <a href="mailto:brimak.maroc@gmail.com" class="hover:text-white">brimak.maroc@gmail.com</a>
                    </p>
                    <p class="text-sm">Tel: 05 24 62 37 75 / 62 60 92</p>
                </div>
                <!-- Column 3: Fax -->
                <div class="space-y-2">
                    <h3 class="font-bold text-lg text-white">Fax</h3>
                    <p class="text-sm">05 24 62 97 91 / 61 25 11</p>
                </div>
            </div>

            <!-- Section 2: Divider Line -->
            <div class="my-8">
                <hr class="border-gray-700">
            </div>

            <!-- Section 3: Copyright & Developer Credit -->
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500 pb-8">
                <p>© <?= date('Y') ?> BRIMAK. All Rights Reserved.</p>
                <p class="mt-2 md:mt-0">
                    Developed by 
                    <a href="https://www.instagram.com/slx7z/" target="_blank" rel="noopener noreferrer" class="hover:text-white font-semibold">
                        @slx7z
                    </a>
                </p>
            </div>

        </div>
    </footer>


    <!-- Link to our main JavaScript file -->
    <script src="assets/js/main.js"></script>
</body>
</html>
<?php
// This is now the ONLY place we close the connection, right at the very end of the script.
if (isset($conn)) {
    $conn->close();
}
?>