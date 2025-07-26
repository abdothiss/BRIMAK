<?php
// dashboards/profile.php (Definitive Version)
$user = get_user();
$profile_error = $_SESSION['profile_error'] ?? null;
$profile_success = $_SESSION['profile_success'] ?? null;
unset($_SESSION['profile_error'], $_SESSION['profile_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Manage Profile - BRIMAK</title><link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100">

    <!-- The profile header is unchanged -->
    <div class="bg-gradient-red-header text-white shadow-lg p-4 relative">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-4"><a href="index.php" class="p-2 -ml-2 rounded-full hover:bg-white/20"><?= icon_arrow_left('w-6 h-6') ?></a><span class="text-xl font-bold">Profile</span><div class="w-6 h-6"></div></div>
            <div class="text-center space-y-2">
                <div class="inline-block bg-white/20 p-1 rounded-full mb-2">
                    <?= icon_user('w-24 h-24') ?>
                </div>
                <h2 class="text-2xl font-bold"><?= e($user['name']) ?></h2><p class="text-white/80">@<?= e($user['username']) ?></p></div>
            <div class="mt-4 pt-4 border-t border-white/20 text-center"><p class="text-sm font-semibold">Role: <span class="font-bold"><?= e($user['role']) ?></span><?php if ($user['section']): ?><span class="mx-2 text-white/50">|</span>Section: <span class="font-bold"><?= e($user['section']) ?></span><?php endif; ?></p></div>
        </div>
    </div>
    
    <div class="container mx-auto p-4 sm:p-6 space-y-4">
        <?php if ($profile_success): ?><div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert"><p><?= e($profile_success) ?></p></div><?php endif; ?>
        <?php if ($profile_error): ?><div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert"><p><?= e($profile_error) ?></p></div><?php endif; ?>

        <!-- ** THIS IS THE ALIGNMENT FIX ** -->
        <div class="bg-white p-4 rounded-lg shadow-md divide-y divide-gray-200">
            <!-- Name Item -->
            <button id="open-name-modal-btn" class="w-full flex justify-between items-center py-3 text-left">
                <!-- This new div groups the text together -->
                <div class="flex-grow">
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-semibold text-gray-800"><?= e($user['name']) ?></p>
                </div>
                <span class="text-gray-400"><?= icon_chevron_right('w-5 h-5') ?></span>
            </button>
            <!-- Username Item -->
            <button id="open-username-modal-btn" class="w-full flex justify-between items-center py-3 text-left">
                <div class="flex-grow">
                    <p class="text-sm text-gray-500">Username</p>
                    <p class="font-semibold text-gray-800">@<?= e($user['username']) ?></p>
                </div>
                <span class="text-gray-400"><?= icon_chevron_right('w-5 h-5') ?></span>
            </button>
            
        <!-- ** THIS IS THE LOGOUT BUTTON COLOR FIX ** -->
        <div class="bg-white p-4 rounded-lg shadow-md">
            <a href="logout.php" class="w-full flex justify-center items-center py-3 text-left font-semibold text-logout-red">
                Logout
            </a>
        </div>
    </div>



</body>
</html>