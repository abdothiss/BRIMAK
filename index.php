<?php
require_once 'includes/functions.php';
require_login();

$user = get_user();
$role = $user['role'];
$view = $_GET['view'] ?? 'dashboard'; // Default view is 'dashboard'

include 'includes/header.php';

// ** NEW, EXPANDED ROUTING LOGIC **
if ($view === 'profile') {
    include 'dashboards/profile.php';
} else {
    // For all other views, we load the main dashboard file.
    // That dashboard file will then decide what content to show.
    $productionRoles = ['Producer', 'Dryer', 'Cooker', 'Presser', 'Packer'];
    if ($role === 'Admin') include 'dashboards/admin.php';
    elseif ($role === 'Commercial') include 'dashboards/commercial.php';
    elseif ($role === 'Chef') include 'dashboards/chef.php';
    elseif (in_array($role, $productionRoles)) include 'dashboards/production.php';
    else echo '<p>No dashboard defined.</p>';
}

include 'includes/footer.php';