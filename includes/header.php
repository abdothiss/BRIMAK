<?php

$user = get_user();
$user_theme = $_SESSION['user_theme'] ?? 'light';

?>
<!DOCTYPE html>
<html lang="<?= e($lang_code) ?>" class="<?= $user_theme === 'dark' ? 'dark' : '' ?>">
<head>
    <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /><title><?= t('page_title') ?></title><link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/ico" href="assets/images/icon.ico">
  </head>
<body class="bg-gray-100 dark:bg-gray-900">
      <div class="grid grid-rows-[auto_1fr_auto] min-h-screen">
        <header class="bg-brick-red text-white shadow-lg sticky top-0 z-20">
          <div class="container mx-auto px-4"><div class="flex items-center justify-between h-16"><div class="flex items-center space-x-4"><button id="menu-btn" class="p-2 -ml-2 rounded-md hover:bg-red-800 focus-outline-none"><?= icon_menu('w-6 h-6') ?></button><a href="index.php?view=dashboard" class="text-xl font-bold tracking-wider">BRIMAK</a></div><a href="index.php?view=profile" class="p-2 rounded-full hover:bg-red-800"><?= icon_user('w-7 h-7') ?></a></div></div>
        </header>

        <div id="menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>
        <div id="menu-panel" class="fixed top-0 left-0 h-full w-72 bg-white shadow-lg z-40 transform -translate-x-full transition-transform duration-300 flex flex-col rounded-r-2xl overflow-hidden">
          <a href="index.php?view=profile" class="p-6 flex items-center space-x-4 bg-gradient-red-header hover:opacity-90 transition-opacity"><div class="bg-white/20 p-1 rounded-full flex-shrink-0"><?= icon_user('w-12 h-12 text-white') ?></div><div><p class="font-bold text-white text-lg"><?= e($user['name']) ?></p><p class="text-sm text-white/80">@<?= e($user['username']) ?></p></div></a>
          <nav class="flex-grow p-4 space-y-2">
            <a href="index.php" class="flex items-center p-3 rounded-md text-gray-700 hover:bg-gray-100"><div class="flex items-center space-x-4"><?= icon_dashboard('w-5 h-5 text-gray-500') ?><span><?= t('menu_dashboard') ?></span></div></a>
            <a href="index.php?view=settings" class="flex items-center p-3 rounded-md text-gray-700 hover:bg-gray-100"><div class="flex items-center space-x-4"><?= icon_settings('w-5 h-5 text-gray-500') ?><span><?= t('menu_settings') ?></span></div></a>
            <?php if (in_array($user['role'], ['Admin', 'Commercial'])): ?>
    <a href="index.php?view=users" class="flex items-center p-3 rounded-md text-gray-700 hover:bg-gray-100">
        <div class="flex items-center space-x-4"><?= icon_users('w-5 h-5 text-gray-500') ?><span><?= t('menu_user_management') ?></span></div>
    </a>
<?php endif; ?>
            <a href="index.php?view=history" class="flex items-center p-3 rounded-md text-gray-700 hover:bg-gray-100"><div class="flex items-center space-x-4"><?= icon_history('w-5 h-5 text-gray-500') ?><span><?= t('menu_command_history') ?></span></div></a>
          </nav>
          <div class="p-4 border-t bg-gray-50 rounded-br-2xl"><a href="logout.php" class="flex items-center p-3 rounded-md text-gray-700 hover:bg-gray-100"><div class="flex items-center space-x-4"><?= icon_logout('w-5 h-5 text-gray-500') ?><span><?= t('menu_logout') ?></span></div></a></div>
        </div>

        <main class="pb-96">
            <div class="container mx-auto p-4 sm:p-6">