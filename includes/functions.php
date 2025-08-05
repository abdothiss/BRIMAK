<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
// ==========================================================
//  NEW, SMARTER LANGUAGE CONTROLLER
// ==========================================================
$available_langs = ['en', 'fr'];
$default_lang = 'fr';

// ** NEW PRIORITY: 1. Logged-in User's DB Preference **
// If the user is logged in, their saved language is the highest priority.
if (isset($_SESSION['user']['language'])) {
    $lang_code = $_SESSION['user']['language'];
} else {
    // Fallback for non-logged-in users (like on the login page)
    $lang_code = $_SESSION['lang'] ?? $default_lang;
}

// ** NEW PRIORITY: 2. URL Override **
// A "?lang=fr" in the URL will always override the current choice for this session.
if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs)) {
    $lang_code = $_GET['lang'];
    $_SESSION['lang'] = $lang_code; // Save for this session
    // If the user is logged in, also update their database preference immediately
    if (isset($_SESSION['user']['id'])) {
        require_once 'db.php'; // Ensure DB is connected
        $stmt = $conn->prepare("UPDATE users SET language = ? WHERE id = ?");
        $stmt->bind_param("si", $lang_code, $_SESSION['user']['id']);
        $stmt->execute();
        // Update the session immediately so the user sees the change everywhere
        $_SESSION['user']['language'] = $lang_code;
    }
}

// 3. Load the correct language file.
$lang_file = __DIR__ . '/../lang/' . $lang_code . '.php';
$lang = file_exists($lang_file) ? require $lang_file : require __DIR__ . '/../lang/' . $default_lang . '.php';

// 4. Create the translation function.
function t($key) {
    global $lang;
    // Return the translation or the key itself in brackets as a fallback
    return $lang[$key] ?? "[$key]"; 
}




if (!isset($_SESSION['user']) && isset($_COOKIE['remember_me'])) {
    // We have a cookie, but no session. Let's try to log the user in.
    list($selector, $validator) = explode(':', $_COOKIE['remember_me'], 2);

    if ($selector && $validator) {
        // Find the token in the database
        $stmt = $conn->prepare("SELECT * FROM auth_tokens WHERE selector = ? AND expires >= NOW()");
        $stmt->bind_param("s", $selector);
        $stmt->execute();
        $token = $stmt->get_result()->fetch_assoc();

        // If a valid
        if ($token) {
            if (password_verify($validator, $token['hashed_validator'])) {
                
                $user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $user_stmt->bind_param("i", $token['user_id']);
                $user_stmt->execute();
                $user = $user_stmt->get_result()->fetch_assoc();

                if ($user) {
                    
                    unset($user['password']); 
                    $_SESSION['user'] = $user;
                }
            }
        }
    }
}

// --- CORE APP FUNCTIONS ---

function require_login() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
}

function get_user() {
    return $_SESSION['user'] ?? null;
}

// --- "REMEMBER ME" TOKEN FUNCTION (Moved from login_action.php) ---
function create_remember_me_token($conn, $user_id) {
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));
    $hashed_validator = password_hash($validator, PASSWORD_DEFAULT);
    $expires = (new DateTime('now'))->add(new DateInterval('P30D')); // Token is good for 30 days

    $stmt = $conn->prepare("INSERT INTO auth_tokens (selector, hashed_validator, user_id, expires) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $selector, $hashed_validator, $user_id, $expires->format('Y-m-d H:i:s'));
    $stmt->execute();
    setcookie('remember_me', $selector . ':' . $validator, time() + 86400 * 90, '/'); // Cookie lasts 90 days
}


// --- CONSTANTS ---
const WORKFLOWS = [
    'A' => ['Chef', 'Producer', 'Dryer', 'Cooker', 'Packer'],
    'B' => ['Chef', 'Presser', 'Cooker']
];
const ALL_ROLES = ['Admin', 'Commercial', 'Chef', 'Producer', 'Dryer', 'Cooker', 'Presser', 'Packer'];
const ALL_STATUSES = ['PendingApproval', 'InProgress', 'Paused', 'Completed', 'Declined', 'Archived'];
// --- UNIVERSAL HELPER FUNCTIONS ---
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// --- ALL ICON FUNCTIONS NOW LIVE HERE PERMANENTLY ---

function icon_user($class = "w-6 h-6") {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
}

function icon_lock($class = 'w-5 h-5') { 
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>'; 
}

function icon_plus($class = 'w-5 h-5') { 
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>'; 
}

function icon_edit($class = 'w-5 h-5') { 
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>'; 
}

function icon_play($class = 'w-4 h-4') { 
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>'; 
}

function icon_pause($class = 'w-4 h-4') { 
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>'; 
}

function icon_x($class = 'w-6 h-6') { 
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>'; 
}

function icon_check($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><polyline points="20 6 9 17 4 12"></polyline></svg>';
}

function icon_menu($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>';
}

function icon_eye($class = 'w-5 h-5') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
}

function icon_eye_off($class = 'w-5 h-5') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
}

function icon_dashboard($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>';
}
function icon_users($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
}
function icon_history($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M1 4v6h6"></path><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>';
}
function icon_logout($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>';
}

function icon_arrow_left($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>';
}

function icon_settings($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>';
}

function icon_chevron_right($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><polyline points="9 18 15 12 9 6"></polyline></svg>';
}

function icon_trash($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
}

function icon_search($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
}

function icon_at_sign($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>';
}

function icon_language($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M5 8h14M12 20l3-3-3-3"></path><path d="M12 4l-3 3 3 3"></path><path d="M20 12H4"></path></svg>';
}

function icon_dots_vertical($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>';
}
function icon_check_circle($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
}
function icon_clock($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
}
function icon_phone($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
}

function icon_arrow_up($class = 'w-6 h-6') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="'.$class.'"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>';
}