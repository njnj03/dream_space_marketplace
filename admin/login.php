<?php
declare(strict_types=1);
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([ 'lifetime'=>0, 'path'=>'/', 'domain'=>'', 'secure'=>$secure, 'httponly'=>true, 'samesite'=>'Lax' ]);
session_start();
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline'; frame-ancestors 'none'; form-action 'self'");

$ADMIN_ID = 'admin';
$ADMIN_PASSWORD = getenv('ADMIN_PASSWORD') ?: 'admin123';

$_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
$_SESSION['lock_until'] = $_SESSION['lock_until'] ?? 0;
$locked = time() < (int)$_SESSION['lock_until'];
$MAX_ATTEMPTS = 10;
$LOCK_SECONDS = 600;
$error = '';

$next = $_GET['next'] ?? '/admin/index.php';
$next = is_string($next) ? $next : '/admin/index.php';
if (!(preg_match('#^/[A-Za-z0-9/_\-.]*$#', $next) || preg_match('#^[A-Za-z0-9/_\-.]*$#', $next))) {
  $next = '/admin/index.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$locked) {
  $token_ok = isset($_POST['csrf']) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
  if (!$token_ok) {
      $error = 'Invalid request. Please try again.';
  } else {
      $userid = trim((string)($_POST['userid'] ?? ''));
      $password = (string)($_POST['password'] ?? '');
      if ($userid === $ADMIN_ID && hash_equals($ADMIN_PASSWORD, $password)) {
          session_regenerate_id(true);
          $_SESSION['admin_logged_in'] = true;
          $_SESSION['admin_id'] = $ADMIN_ID;
          $_SESSION['login_attempts'] = 0;
          $_SESSION['lock_until'] = 0;
          header('Location: '.$next);
          exit;
      } else {
          $_SESSION['login_attempts']++;
          if ($_SESSION['login_attempts'] >= $MAX_ATTEMPTS) {
              $_SESSION['lock_until'] = time() + $LOCK_SECONDS;
          }
          $error = 'Invalid credentials.';
      }
  }
}
if (time() < (int)$_SESSION['lock_until']) { $error = 'Too many attempts. Try again later.'; }
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
$csrf = $_SESSION['csrf'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DreamSpace Admin Login</title>
  <meta name="robots" content="noindex, nofollow">
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; padding: 2rem; background: #f6f8fa; }
    .card { max-width: 420px; margin: 4rem auto; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.04); }
    .card h1 { font-size: 1.25rem; margin: 0; }
    .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid #eee; }
    .card-body { padding: 1.25rem; }
    .field { margin-bottom: 1rem; }
    label { display:block; font-weight:600; margin-bottom: .25rem; }
    input[type=text], input[type=password] { width: 100%; padding: .625rem .75rem; border:1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
    .btn { display:inline-block; padding: .6rem 1rem; border: 0; background:#111827; color:#fff; border-radius: 8px; cursor:pointer; font-weight:600; }
    .muted { color:#6b7280; font-size:.9rem; }
    .error { background:#fee2e2; color:#991b1b; padding:.75rem; border-radius:8px; border:1px solid #fecaca; margin-bottom:1rem; }
    .note { background:#eff6ff; color:#1e40af; padding:.75rem; border-radius:8px; border:1px solid #bfdbfe; margin-top:1rem; }
  </style>
</head>
<body>
  <div class="card">
    <div class="card-header">
      <h1>DreamSpace Realty — Administrator Login</h1>
    </div>
    <div class="card-body">
      <?php if (!empty($_GET['logged_out'])): ?>
        <div class="note">You have been logged out.</div>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>
      <form method="post" action="">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="next" value="<?php echo htmlspecialchars($next, ENT_QUOTES, 'UTF-8'); ?>">
        <div class="field">
          <label for="userid">User ID</label>
          <input id="userid" name="userid" type="text" autocomplete="username" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" autocomplete="current-password" required>
        </div>
        <button class="btn" type="submit" <?php echo (time() < (int)$_SESSION['lock_until']) ? 'disabled' : ''; ?>>Sign In</button>
        <!-- <p class="muted" style="margin-top:.75rem;">Demo credentials (change before deploy): <strong>admin / admin123</strong></p> -->
      </form>
    </div>
  </div>
</body>
</html>
