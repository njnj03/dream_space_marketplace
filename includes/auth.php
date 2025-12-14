<?php
// Public user authentication functions
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// User data file
define('USERS_FILE', __DIR__ . '/../data/users.txt');

/**
 * Check if user is logged in
 */
function is_user_logged_in() {
    return !empty($_SESSION['user_logged_in']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user data
 */
function get_current_user() {
    if (!is_user_logged_in()) {
        return null;
    }
    
    $userId = $_SESSION['user_id'];
    $users = get_all_users();
    
    foreach ($users as $user) {
        if ($user['username'] === $userId) {
            return $user;
        }
    }
    
    return null;
}

/**
 * Get all users from file
 * Handles both old format (Name | Username | Role) and new format (Name | Username | PasswordHash | Role | Email)
 */
function get_all_users() {
    $users = [];
    
    if (file_exists(USERS_FILE)) {
        $lines = file(USERS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = array_map('trim', explode('|', $line));
            
            // New format: Name | Username | PasswordHash | Role | Email (4+ parts)
            if (count($parts) >= 4) {
                // Check if part[2] looks like a password hash (starts with $2y$ or similar)
                if (strpos($parts[2], '$2y$') === 0 || strpos($parts[2], '$2a$') === 0 || strpos($parts[2], '$2b$') === 0) {
                    // New format with password hash
                    $users[] = [
                        'name' => $parts[0],
                        'username' => $parts[1],
                        'password_hash' => $parts[2],
                        'role' => $parts[3],
                        'email' => $parts[4] ?? ''
                    ];
                } else {
                    // Old format: Name | Username | Role (3 parts)
                    $users[] = [
                        'name' => $parts[0],
                        'username' => $parts[1],
                        'password_hash' => '', // No password hash for old users
                        'role' => $parts[2],
                        'email' => ''
                    ];
                }
            } elseif (count($parts) >= 3) {
                // Old format: Name | Username | Role
                $users[] = [
                    'name' => $parts[0],
                    'username' => $parts[1],
                    'password_hash' => '', // No password hash for old users
                    'role' => $parts[2],
                    'email' => ''
                ];
            }
        }
    }
    
    return $users;
}

/**
 * Find user by username
 */
function find_user_by_username($username) {
    $users = get_all_users();
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return $user;
        }
    }
    return null;
}

/**
 * Check if username exists
 */
function username_exists($username) {
    return find_user_by_username($username) !== null;
}

/**
 * Register a new user
 */
function register_user($name, $username, $password, $email = '', $role = 'user') {
    // Validate input
    if (empty($name) || empty($username) || empty($password)) {
        return ['success' => false, 'error' => 'All required fields must be filled.'];
    }
    
    if (strlen($username) < 3) {
        return ['success' => false, 'error' => 'Username must be at least 3 characters.'];
    }
    
    if (strlen($password) < 6) {
        return ['success' => false, 'error' => 'Password must be at least 6 characters.'];
    }
    
    if (username_exists($username)) {
        return ['success' => false, 'error' => 'Username already exists.'];
    }
    
    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Format: Name | Username | PasswordHash | Role | Email
    $user_line = $name . ' | ' . $username . ' | ' . $password_hash . ' | ' . $role . ' | ' . $email . PHP_EOL;
    
    // Append to file
    if (file_put_contents(USERS_FILE, $user_line, FILE_APPEND | LOCK_EX) === false) {
        return ['success' => false, 'error' => 'Failed to save user. Please try again.'];
    }
    
    return ['success' => true, 'message' => 'Registration successful!'];
}

/**
 * Login user
 */
function login_user($username, $password) {
    $user = find_user_by_username($username);
    
    if (!$user) {
        return ['success' => false, 'error' => 'Invalid username or password.'];
    }
    
    // Check if user has a password hash (new format)
    if (empty($user['password_hash'])) {
        return ['success' => false, 'error' => 'This account needs to be registered. Please use the registration page to set a password.'];
    }
    
    if (!password_verify($password, $user['password_hash'])) {
        return ['success' => false, 'error' => 'Invalid username or password.'];
    }
    
    // Regenerate session ID for security
    session_regenerate_id(true);
    
    // Set session variables
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id'] = $user['username'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
    
    return ['success' => true, 'user' => $user];
}

/**
 * Logout user
 */
function logout_user() {
    $_SESSION['user_logged_in'] = false;
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_role']);
    session_destroy();
}

/**
 * Require user to be logged in
 */
function require_user_login($redirect_to = '/login.php') {
    if (!is_user_logged_in()) {
        $current_url = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: ' . $redirect_to . '?next=' . urlencode($current_url));
        exit;
    }
}

/**
 * Handle Group Marketplace user authentication
 * This function should be called on every page to automatically log in marketplace users
 * According to for-your-teammates.md Section 8
 */
function handle_marketplace_authentication() {
    // Check if marketplace user ID is in URL parameters
    $marketplaceUserId = $_GET['marketplace_user_id'] ?? null;
    $hash = $_GET['hash'] ?? '';
    
    // If marketplace user ID is present, validate and create session
    if ($marketplaceUserId) {
        $expectedHash = md5($marketplaceUserId . 'marketplace_secret_2025');
        
        if ($hash === $expectedHash) {
            // Valid marketplace user!
            // Store marketplace user info in session
            $_SESSION['marketplace_user_id'] = $marketplaceUserId;
            $_SESSION['is_marketplace_user'] = true;
            
            // Create or update local user account for marketplace user
            $localUsername = 'marketplace_' . $marketplaceUserId;
            $user = find_user_by_username($localUsername);
            
            if (!$user) {
                // Create new local user account for marketplace user
                $name = 'Marketplace User ' . $marketplaceUserId;
                $email = 'marketplace_' . $marketplaceUserId . '@marketplace.local';
                
                // Create a random password (user won't need to use it)
                $randomPassword = bin2hex(random_bytes(16));
                $password_hash = password_hash($randomPassword, PASSWORD_DEFAULT);
                
                // Format: Name | Username | PasswordHash | Role | Email
                $user_line = $name . ' | ' . $localUsername . ' | ' . $password_hash . ' | marketplace_user | ' . $email . PHP_EOL;
                
                // Append to file
                if (file_put_contents(USERS_FILE, $user_line, FILE_APPEND | LOCK_EX) !== false) {
                    // User created successfully
                }
            }
            
            // Auto-login the marketplace user as local user
            $localUser = find_user_by_username($localUsername);
            if ($localUser) {
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                // Set session variables for local login
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $localUser['username'];
                $_SESSION['user_name'] = $localUser['name'];
                $_SESSION['user_role'] = $localUser['role'];
            }
        }
    }
    
    // Also check if user is already logged in via marketplace session
    // This allows the session to persist across pages
    if (isset($_SESSION['marketplace_user_id']) && !is_user_logged_in()) {
        $localUsername = 'marketplace_' . $_SESSION['marketplace_user_id'];
        $localUser = find_user_by_username($localUsername);
        
        if ($localUser) {
            // Auto-login the marketplace user
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $localUser['username'];
            $_SESSION['user_name'] = $localUser['name'];
            $_SESSION['user_role'] = $localUser['role'];
        }
    }
}

/**
 * Check if current user is a marketplace user
 */
function is_marketplace_user() {
    return !empty($_SESSION['is_marketplace_user']) && !empty($_SESSION['marketplace_user_id']);
}

/**
 * Get marketplace user ID
 */
function get_marketplace_user_id() {
    return $_SESSION['marketplace_user_id'] ?? null;
}

// Automatically handle marketplace authentication on every page load
handle_marketplace_authentication();
?>

