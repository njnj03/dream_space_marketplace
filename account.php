<?php $page='account'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<?php
// auth.php is already included in header.php
require_user_login();

$user = get_current_user();
?>

<h1>My Account</h1>
<p class="lead">Welcome back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>!</p>

<?php if (is_marketplace_user()): ?>
    <div class="card" style="background: #dbeafe; border: 2px solid #93c5fd; margin-bottom: 24px;">
        <h3>🌐 Group Marketplace User</h3>
        <p>You're logged in via Group Marketplace. Your account is linked to marketplace user ID: <strong><?= htmlspecialchars(get_marketplace_user_id()) ?></strong></p>
    </div>
<?php endif; ?>

<div class="grid cols-3">
    <div class="card">
        <h3>Profile Information</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name'] ?? 'N/A') ?></p>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username'] ?? 'N/A') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'Not provided') ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($user['role'] ?? 'user') ?></p>
    </div>
    
    <div class="card">
        <h3>Quick Actions</h3>
        <p><a class="btn" href="recent.php">View Recent Visits</a></p>
        <p><a class="btn" href="listings.php" style="background: #374151; margin-top: 8px;">Browse Services</a></p>
        <p><a class="btn" href="properties.php" style="background: #059669; margin-top: 8px;">View Properties</a></p>
    </div>
    
    <div class="card">
        <h3>Account Management</h3>
        <p><a class="btn" href="logout.php" style="background: #dc2626; margin-top: 8px;">Sign Out</a></p>
    </div>
</div>

<?php if (isset($_GET['logged_out'])): ?>
    <div class="notice" style="background: #d1fae5; border-color: #86efac; color: #065f46; margin-top: 24px;">
        You have been successfully logged out.
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

