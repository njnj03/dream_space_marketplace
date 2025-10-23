<?php
require __DIR__ . '/auth.php';
require_login();
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline'; frame-ancestors 'none'");

$users_file = __DIR__ . '/../data/users.txt';
$users = [];
if (file_exists($users_file)) {
    $lines = file($users_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (count($parts) >= 3) {
            $users[] = ['name' => $parts[0], 'username' => $parts[1], 'role' => $parts[2]];
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DreamSpace Admin — Users</title>
  <meta name="robots" content="noindex, nofollow">
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; padding: 2rem; background: #f9fafb; }
    header { display:flex; align-items:center; justify-content:space-between; max-width: 960px; margin: 0 auto 1rem; }
    .logout { color:#ef4444; text-decoration:none; font-weight:600; }
    .table { max-width: 960px; margin: 0 auto; background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,.04); }
    table { width:100%; border-collapse: collapse; }
    th, td { padding: .9rem 1rem; border-bottom:1px solid #eee; text-align:left; }
    th { background:#f3f4f6; font-size:.95rem; }
    tr:last-child td { border-bottom:0; }
    .muted { color:#6b7280; }
    .chip { display:inline-block; padding: .2rem .5rem; border-radius:9999px; border:1px solid #e5e7eb; font-size:.85rem; background:#f9fafb; }
  </style>
</head>
<body>
  <header>
    <h1>Secure Section — Current Users</h1>
    <nav>
      <a class="logout" href="logout.php">Log out</a>
    </nav>
  </header>

  <div class="table">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Username</th>
          <th>Role</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr><td colspan="3" class="muted">No users found. Create /data/users.txt with lines in "Name | Username | Role" format.</td></tr>
        <?php else: ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?php echo htmlspecialchars($u['name']); ?></td>
              <td><?php echo htmlspecialchars($u['username']); ?></td>
              <td><span class="chip"><?php echo htmlspecialchars($u['role']); ?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <p class="muted" style="max-width:960px;margin:1rem auto 0;">
    You are logged in as <strong><?php echo htmlspecialchars($_SESSION['admin_id'] ?? 'admin'); ?></strong>.
  </p>
</body>
</html>
