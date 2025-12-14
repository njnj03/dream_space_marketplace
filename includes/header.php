<?php
  // Include auth functions first to handle marketplace authentication
  require_once __DIR__ . '/auth.php';
  
  if (!isset($page)) $page = "";
  if (!isset($companyName)) $companyName = "DreamSpace Realty";
  $title = $companyName . " — " . ucfirst($page);
?>
<!doctype html>
<html lang="en">
  <head>
  <meta name="google-site-verification" content="llyloLTciNgvL5VzD3w6gehJFp_Rfau_3SPNaaHCi9Y" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="description" content="DreamSpace Realty — modern listings, expert agents, and helpful resources.">
    <link rel="stylesheet" href="assets/style.css?v=<?= time() ?>">
  </head>
  <body>
    <div id="loading-screen">
      <div class="loading-content">
        <svg class="loading-logo" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="8" y="24" width="48" height="28" rx="3" stroke="currentColor" stroke-width="4"/>
          <path d="M8 28 L32 10 L56 28" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <h2 class="loading-text">DreamSpace Realty</h2>
      </div>
    </div>
    <nav class="site-nav">
      <div class="nav-inner">
        <a class="brand" href="index.php" aria-label="<?= htmlspecialchars($companyName) ?>">
          <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="8" y="24" width="48" height="28" rx="3" stroke="currentColor" stroke-width="4"/>
            <path d="M8 28 L32 10 L56 28" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span><?= htmlspecialchars($companyName) ?></span>
        </a>
        <div class="nav-tabs">
          <div class="menu" role="navigation" aria-label="Main">
            <a href="index.php" class="<?= $page==='home' ? 'active' : '' ?>">Home</a>
            <a href="about.php" class="<?= $page==='about' ? 'active' : '' ?>">About</a>
            <a href="listings.php" class="<?= $page==='services' ? 'active' : '' ?>">Services</a>
            <a href="properties.php" class="<?= $page==='properties' ? 'active' : '' ?>">Properties</a>
            <a href="news.php" class="<?= $page==='news' ? 'active' : '' ?>">News</a>
            <a href="contacts.php" class="<?= $page==='contacts' ? 'active' : '' ?>">Contacts</a>
            <?php
            // auth.php is already included at the top of this file
            if (function_exists('is_user_logged_in') && is_user_logged_in()): 
              $userName = $_SESSION['user_name'] ?? 'User';
            ?>
                <a href="https://cmpe272groupproject.infinityfree.me/wishlist.php" target="_blank">Wishlist</a>
                <a href="https://cmpe272groupproject.infinityfree.me/browsing-history.php" target="_blank">History</a>
            <?php endif; ?>
            <a href="admin/login.php" class="admin-link">Admin</a>
            <?php if (function_exists('is_user_logged_in') && is_user_logged_in()): ?>
                <div class="profile-dropdown">
                  <button class="profile-btn" aria-expanded="false" aria-haspopup="true">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;">
                      <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
                      <path d="M4 20c0-4 3.6-6 8-6s8 2 8 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span><?= htmlspecialchars($userName) ?></span>
                    <svg class="dropdown-arrow" viewBox="0 0 16 16" fill="currentColor" style="width:12px;height:12px;">
                      <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    </svg>
                  </button>
                  <div class="dropdown-menu">
                    <a href="account.php" class="dropdown-item">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;">
                        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
                        <path d="M4 20c0-4 3.6-6 8-6s8 2 8 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                      My Account
                    </a>
                    <a href="logout.php" class="dropdown-item">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                      Sign Out
                    </a>
                  </div>
                </div>
              <?php else: ?>
                <a href="login.php" class="<?= $page==='login' ? 'active' : '' ?>">Sign In</a>
                <a href="register.php" class="<?= $page==='register' ? 'active' : '' ?>">Register</a>
              <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>
    <main class="container">