<?php
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
function is_logged_in() { return !empty($_SESSION['admin_logged_in']); }
function require_login() {
    if (!is_logged_in()) {
        $next = $_SERVER['REQUEST_URI'] ?? '/admin/index.php';
        header('Location: /admin/login.php?next=' . urlencode($next));
        exit;
    }
}
