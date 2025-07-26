<?php
require_once 'includes/functions.php';
if (isset($_SESSION['user'])) { header("Location: index.php"); exit(); }
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIMAK Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page-body">

    <!-- MOBILE VIEW (Your code is unchanged) -->
    <div class="flex items-center justify-center min-h-screen pt-64 p-4 md:hidden">
        <!-- ** CHANGE 1: Padding reduced from p-8 to p-6 ** -->
        <div class="w-full max-w-xs mx-auto rounded-2xl p-6 glass-form">
            <!-- ** CHANGE 2: Font size reduced from text-4xl to text-3xl ** -->
            <h1 class="text-3xl font-bold text-center text-white">Login</h1>
            <!-- ** CHANGE 3: Font size reduced to text-sm ** -->
            <p class="text-center text-gray-200 mt-2 text-sm">Welcome back, please login to your account.</p>
            
            <!-- ** CHANGE 4: Vertical spacing reduced from space-y-6 to space-y-5 ** -->
            <form id="mobile-login-form" action="actions/login_action.php" method="POST" class="mt-6 space-y-5">
                <div class="relative">
                    <!-- ** CHANGE 5: Input padding reduced from py-3 to py-2.5 ** -->
                    <input name="username" type="text" required class="w-full pl-4 pr-10 py-2.5 rounded-md glass-input" placeholder="User Name">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-200"><?= icon_user('w-5 h-5') ?></div>
                </div>
                <div class="relative">
                    <input id="password-mobile" name="password" type="password" required class="w-full pl-4 pr-10 py-2.5 rounded-md glass-input" placeholder="Password">
                    <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-200">
                        <span class="eye-icon"><?= icon_eye('w-5 h-5') ?></span><span class="eye-off-icon hidden"><?= icon_eye_off('w-5 h-5') ?></span>
                    </button>
                </div>
                <?php if ($error): ?><p class="text-sm text-center text-red-300"><?= e($error) ?></p><?php endif; ?>
                <div class="flex items-center"><input name="remember_me" type="checkbox" class="h-4 w-4 text-green-500 rounded"><label class="ml-2 block text-sm text-white">Remember me</label></div>
                <div>
                    <!-- ** CHANGE 6: Button padding and font size reduced ** -->
                    <button type="submit" class="login-button w-full flex justify-center py-2.5 px-4 rounded-md shadow-sm text-base font-bold text-white btn-gradient-red"><span class="login-button-text">Login</span></button>
                </div>
            </form>
        </div>
    </div>

    <!-- DESKTOP VIEW (Your code is unchanged) -->
    <div class="hidden md:flex w-full min-h-screen">
        <div class="w-1/2 flex items-center justify-center p-12">
            <div class="w-full max-w-sm mx-auto rounded-2xl p-8 desktop-form">
                <h1 class="text-4xl font-bold text-center text-white">Login</h1>
                <p class="text-center text-gray-200 mt-2">Welcome back, please login to your account.</p>
                <form id="desktop-login-form" action="actions/login_action.php" method="POST" class="mt-8 space-y-6">
                    <div class="relative"><input name="username" type="text" required class="w-full pl-4 pr-10 py-3 rounded-md desktop-input" placeholder="User Name"><div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-200"><?= icon_user('w-5 h-5') ?></div></div>
                    <div class="relative"><input id="password-desktop" name="password" type="password" required class="w-full pl-4 pr-10 py-3 rounded-md desktop-input" placeholder="Password"><button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-200"><span class="eye-icon"><?= icon_eye('w-5 h-5') ?></span><span class="eye-off-icon hidden"><?= icon_eye_off('w-5 h-5') ?></span></button></div>
                    <?php if ($error): ?><p class="text-sm text-center text-red-300"><?= e($error) ?></p><?php endif; ?>
                    <div class="flex items-center"><input name="remember_me" type="checkbox" class="h-4 w-4 text-green-500 rounded"><label class="ml-2 block text-sm text-white">Remember me</label></div>
                    <div><button type="submit" class="login-button w-full flex justify-center py-3 px-4 rounded-md shadow-sm text-lg font-bold text-white btn-gradient-red"><span class="login-button-text">Login</span></button></div>
                </form>
            </div>
        </div>
        <div class="w-1/2"></div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <!-- ** THIS IS THE CRITICAL FIX ** -->
    <script>
        // This universal script works for both mobile and desktop forms.
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                // Find the password input field that is the direct sibling BEFORE this button.
                const passwordInput = this.previousElementSibling;
                
                // Toggle the type attribute between 'password' and 'text'.
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Find the icons INSIDE this button and toggle which one is hidden.
                this.querySelector('.eye-icon').classList.toggle('hidden');
                this.querySelector('.eye-off-icon').classList.toggle('hidden');
            });
        });
        
        // This is your working spinner logic, it remains unchanged.
        document.querySelectorAll('.login-button').forEach(btn => {
            btn.closest('form').addEventListener('submit', function() {
                btn.disabled = true;
                btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...`;
            });
        });
    </script>
</body>
</html>