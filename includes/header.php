<?php
require_login();
$user = get_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>BRIMAK Command Tracking</title><link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100">
    <div class="flex flex-col min-h-screen">
        <header class="bg-brick-red text-white shadow-lg sticky top-0 z-20">
          <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
              <div class="flex items-center space-x-4">
                <button id="menu-btn" class="p-2 -ml-2 rounded-md hover:bg-red-800 focus:outline-none"><?= icon_menu('w-6 h-6') ?></button>
                <h1 class="text-xl font-bold tracking-wider">BRIMAK</h1>
              </div>
              <a href="index.php?view=profile" class="p-2 rounded-full hover:bg-red-800"><?= icon_user('w-7 h-7') ?></a>
            </div>
          </div>
        </header>

        <div id="menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>
        <div id="menu-panel" class="fixed top-0 left-0 h-full w-72 bg-white shadow-lg z-40 transform -translate-x-full transition-transform duration-300 flex flex-col ">
          <div class="p-4 border-b"><h2 class="text-xl font-bold text-brick-red">Menu</h2></div>
          <nav class="flex-grow p-4 space-y-2">
            <a href="index.php" class="flex items-center space-x-3 p-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <?= icon_dashboard('w-5 h-5 text-gray-500') ?> <span>Dashboard</span>
            </a>
            <a href="index.php?view=profile" class="flex items-center space-x-3 p-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <?= icon_user('w-5 h-5 text-gray-500') ?> <span>Manage Profile</span>
            </a>
            <?php if ($user['role'] === 'Admin'): ?>
                <a href="index.php?view=users" class="flex items-center space-x-3 p-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                    <?= icon_users('w-5 h-5 text-gray-500') ?> <span>User Management</span>
                </a>
            <?php endif; ?>
            <?php if (in_array($user['role'], ['Admin', 'Commercial'])): ?>
                <a href="index.php?view=history" class="flex items-center space-x-3 p-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                    <?= icon_history('w-5 h-5 text-gray-500') ?> <span>Command History</span>
                </a>
            <?php endif; ?>
          </nav>

          <div class="p-4 border-t bg-gray-50">
            <a href="logout.php" class="flex items-center space-x-3 p-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <?= icon_logout('w-5 h-5 text-gray-500') ?> <span>Logout</span>
            </a>
          </div>
        </div>

        <main class="flex-grow">
            <div class="container mx-auto p-4 sm:p-6">