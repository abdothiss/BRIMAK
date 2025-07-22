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

    <!-- MOBILE VIEW (Unchanged and correct) -->
    <div class="flex items-center justify-center min-h-screen pt-64 p-4 md:hidden">
        <div class="w-full max-w-sm mx-auto rounded-2xl p-8 glass-form">
            <h1 class="text-4xl font-bold text-center text-white">Login</h1>
            <p class="text-center text-gray-200 mt-4">Welcome back, please login to your account.</p>
            <form id="mobile-login-form" action="actions/login_action.php" method="POST" class="mt-8 space-y-6">
                <div class="relative">
                    <input name="username" type="text" required class="w-full pl-4 pr-10 py-3 rounded-md glass-input" placeholder="User Name">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-200"><?= icon_user('w-5 h-5') ?></div>
                </div>
                <div class="relative">
                    <input id="password-mobile" name="password" type="password" required class="w-full pl-4 pr-10 py-3 rounded-md glass-input" placeholder="Password">
                    <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-200">
                        <span class="eye-icon"><?= icon_eye('w-5 h-5') ?></span><span class="eye-off-icon hidden"><?= icon_eye_off('w-5 h-5') ?></span>
                    </button>
                </div>
                <?php if ($error): ?><p class="text-sm text-center text-red-300"><?= e($error) ?></p><?php endif; ?>
                <div class="flex items-center"><input name="remember_me" type="checkbox" class="h-4 w-4 text-green-500 rounded"><label class="ml-2 block text-sm text-white">Remember me</label></div>
                <div><button type="submit" class="login-button w-full flex justify-center py-3 px-4 rounded-md shadow-sm text-lg font-bold text-white btn-gradient-red"><span class="login-button-text">Login</span></button></div>
            </form>
        </div>
    </div>

    <!-- DESKTOP VIEW (With corrected text colors) -->
    <div class="hidden md:flex w-full min-h-screen">
        <div class="w-1/2 flex items-center justify-center p-12">
            <div class="w-full max-w-sm mx-auto rounded-2xl p-8 desktop-form">
                <!-- ** CHANGE 1: Text color is now white ** -->
                <h1 class="text-4xl font-bold text-center text-white">Login</h1>
                <!-- ** CHANGE 2: Text color is now light gray ** -->
                <p class="text-center text-gray-200 mt-2">Welcome back, please login to your account.</p>
                <form id="desktop-login-form" action="actions/login_action.php" method="POST" class="mt-8 space-y-6">
                    <div class="relative"><input name="username" type="text" required class="w-full pl-4 pr-10 py-3 rounded-md desktop-input" placeholder="User Name"><div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-200"><?= icon_user('w-5 h-5') ?></div></div>
                    <div class="relative"><input id="password-desktop" name="password" type="password" required class="w-full pl-4 pr-10 py-3 rounded-md desktop-input" placeholder="Password"><button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-200"><span class="eye-icon"><?= icon_eye('w-5 h-5') ?></span><span class="eye-off-icon hidden"><?= icon_eye_off('w-5 h-5') ?></span></button></div>
                    <!-- ** CHANGE 3: Error text is now a lighter red for better contrast ** -->
                    <?php if ($error): ?><p class="text-sm text-center text-red-300"><?= e($error) ?></p><?php endif; ?>
                    <!-- ** CHANGE 4: Label text is now white ** -->
                    <div class="flex items-center"><input name="remember_me" type="checkbox" class="h-4 w-4 text-green-500 rounded"><label class="ml-2 block text-sm text-white">Remember me</label></div>
                    <div><button type="submit" class="login-button w-full flex justify-center py-3 px-4 rounded-md shadow-sm text-lg font-bold text-white btn-gradient-red"><span class="login-button-text">Login</span></button></div>
                </form>
            </div>
        </div>
        <div class="w-1/2">
            <!-- This side is intentionally left blank -->
        </div>
    </div>
    <div class="h-20 md:h-32"></div>
    <?php
    // We now include the main footer file here
    include 'includes/footer.php';
    ?>
    <script>
        // This universal script is correct and remains unchanged.
        document.querySelectorAll('.password-toggle').forEach(btn => { /* ... */ });
        document.querySelectorAll('.login-button').forEach(btn => { /* ... */ });
    </script>
</body>
</html>