<?php
// index.php (Definitive Version)

require_once 'includes/functions.php';
require_login();

$user = get_user();
$role = $user['role'];
$view = $_GET['view'] ?? 'dashboard';

// ** THIS IS THE CRITICAL LOGIC **
// If the view is NOT the profile page, we include the standard header.
if ($view !== 'profile') {
    include 'includes/header.php';
}

// Now we route to the correct content file.
if ($view === 'profile') {
    // The profile.php file will now be responsible for its own complete HTML structure.
    include 'dashboards/profile.php';
} else {
    // For all other views, we load the user's main dashboard file.
    $productionRoles = ['Producer', 'Dryer', 'Cooker', 'Presser', 'Packer'];
    if ($role === 'Admin') include 'dashboards/admin.php';
    elseif ($role === 'Commercial') include 'dashboards/commercial.php';
    elseif ($role === 'Chef') include 'dashboards/chef.php';
    elseif (in_array($role, $productionRoles)) include 'dashboards/production.php';
    else echo '<p>No dashboard defined.</p>';
}

// If the view is NOT the profile page, we include the standard footer.
if ($view !== 'profile') {
    include 'includes/footer.php';
}