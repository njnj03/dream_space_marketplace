<?php $page='register'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<?php
require_once 'includes/auth.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    
    $result = register_user($name, $username, $password, $email);
    
    if ($result['success']) {
        $success = $result['message'];
        // Auto-login after registration
        $login_result = login_user($username, $password);
        if ($login_result['success']) {
            header('Location: account.php');
            exit;
        }
    } else {
        $error = $result['error'];
    }
}
?>

<h1>Create Account</h1>
<p class="lead">Join DreamSpace Realty to access exclusive features and manage your preferences.</p>

<?php if ($error): ?>
    <div class="notice error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="notice" style="background: #d1fae5; border-color: #86efac; color: #065f46;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <form method="post" action="">
        <div style="margin-bottom: 16px;">
            <label for="name" style="display: block; margin-bottom: 4px; font-weight: 600;">Full Name *</label>
            <input type="text" id="name" name="name" required 
                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px;"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        
        <div style="margin-bottom: 16px;">
            <label for="username" style="display: block; margin-bottom: 4px; font-weight: 600;">Username *</label>
            <input type="text" id="username" name="username" required 
                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px;"
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                   pattern="[a-zA-Z0-9_]{3,}" title="Username must be at least 3 characters, letters, numbers, and underscores only">
            <small style="color: #64748b; font-size: 14px;">At least 3 characters, letters, numbers, and underscores only</small>
        </div>
        
        <div style="margin-bottom: 16px;">
            <label for="email" style="display: block; margin-bottom: 4px; font-weight: 600;">Email</label>
            <input type="email" id="email" name="email" 
                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px;"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        
        <div style="margin-bottom: 16px;">
            <label for="password" style="display: block; margin-bottom: 4px; font-weight: 600;">Password *</label>
            <input type="password" id="password" name="password" required 
                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px;"
                   minlength="6">
            <small style="color: #64748b; font-size: 14px;">At least 6 characters</small>
        </div>
        
        <button type="submit" class="btn" style="width: 100%; margin-top: 8px;">Create Account</button>
    </form>
    
    <p style="margin-top: 24px; text-align: center; color: #64748b;">
        Already have an account? <a href="login.php">Sign in here</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>


