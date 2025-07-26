<?php
// index.php (Definitive, Corrected Version)

require_once 'includes/functions.php';
require_login();

$user = get_user();
$role = $user['role'];
$view = $_GET['view'] ?? 'dashboard';

// This is a special case ONLY for the profile page, which has a unique full-screen design.
if ($view === 'profile') {
    include 'dashboards/profile.php';
    // The script stops here because profile.php is a complete HTML page.
    exit();
}

// For ALL other pages (including our settings page), we load the standard layout.
include 'includes/header.php';

// This is our main router for all content that goes inside the standard layout.
switch ($view) {
    case 'settings':
        // The settings page is now correctly loaded here, inside the main layout.
        include 'dashboards/settings.php';
        break;

    case 'users':
        if ($role === 'Admin') {
            include 'dashboards/admin.php'; // The admin file handles its own 'users' view.
        } else {
            // A non-admin trying to access this is safely sent back to their dashboard.
            header('Location: index.php');
            exit();
        }
        break;

    case 'history':
        // This logic correctly sends each role to their respective dashboard file to handle the history view.
        if ($role === 'Admin') include 'dashboards/admin.php';
        elseif ($role === 'Commercial') include 'dashboards/commercial.php';
        elseif ($role === 'Chef') include 'dashboards/chef.php';
        elseif (in_array($role, ['Producer', 'Dryer', 'Cooker', 'Presser', 'Packer'])) include 'dashboards/production.php';
        break;
    
    default: // This is for 'dashboard' or any other value
        $productionRoles = ['Producer', 'Dryer', 'Cooker', 'Presser', 'Packer'];
        if ($role === 'Admin') include 'dashboards/admin.php';
        elseif ($role === 'Commercial') include 'dashboards/commercial.php';
        elseif ($role === 'Chef') include 'dashboards/chef.php';
        elseif (in_array($role, $productionRoles)) include 'dashboards/production.php';
        else echo '<p>No dashboard defined.</p>';
        break;
}

// Finally, we include the footer to complete the page.
include 'includes/footer.php';