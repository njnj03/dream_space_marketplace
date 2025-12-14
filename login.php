<?php $page='login'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<?php
require_once 'includes/auth.php';

// Redirect if already logged in
if (is_user_logged_in()) {
    header('Location: account.php');
    exit;
}

$error = '';
$next = $_GET['next'] ?? 'account.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $result = login_user($username, $password);
        
        if ($result['success']) {
            $redirect = $_POST['next'] ?? $next;
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = $result['error'];
        }
    }
}
?>

<h1>Sign In</h1>
<p class="lead">Sign in to your DreamSpace Realty account to access your profile and preferences.</p>

<?php if ($error): ?>
    <div class="notice error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <form method="post" action="">
        <input type="hidden" name="next" value="<?= htmlspecialchars($next) ?>">
        
        <div style="margin-bottom: 16px;">
            <label for="username" style="display: block; margin-bottom: 4px; font-weight: 600;">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username"
                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px;"
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
        
        <div style="margin-bottom: 16px;">
            <label for="password" style="display: block; margin-bottom: 4px; font-weight: 600;">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password"
                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px;">
        </div>
        
        <button type="submit" class="btn" style="width: 100%; margin-top: 8px;">Sign In</button>
    </form>
    
    <p style="margin-top: 24px; text-align: center; color: #64748b;">
        Don't have an account? <a href="register.php">Create one here</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>


