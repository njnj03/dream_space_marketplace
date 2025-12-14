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
    <link rel="stylesheet" href="assets/style.css">
  </head>
  <body>
    <nav class="site-nav">
      <div class="nav-inner">
        <a class="brand" href="index.php" aria-label="<?= htmlspecialchars($companyName) ?>">
          <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="8" y="24" width="48" height="28" rx="3" stroke="currentColor" stroke-width="4"/>
            <path d="M8 28 L32 10 L56 28" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span><?= htmlspecialchars($companyName) ?></span>
        </a>
        <div class="menu" role="navigation" aria-label="Main">
          <a href="index.php" class="<?= $page==='home' ? 'active' : '' ?>">Home</a>
          <a href="about.php" class="<?= $page==='about' ? 'active' : '' ?>">About</a>
          <a href="listings.php" class="<?= $page==='services' ? 'active' : '' ?>">Services</a>
          <a href="properties.php" class="<?= $page==='properties' ? 'active' : '' ?>">Properties</a>
          <a href="users.php" class="<?= $page==='users' ? 'active' : '' ?>">Users</a>
          <a href="news.php" class="<?= $page==='news' ? 'active' : '' ?>">News</a>
          <a href="contacts.php" class="<?= $page==='contacts' ? 'active' : '' ?>">Contacts</a>
          <?php
          // auth.php is already included at the top of this file
          if (function_exists('is_user_logged_in') && is_user_logged_in()): ?>
              <a href="account.php" class="<?= $page==='account' ? 'active' : '' ?>">My Account</a>
              <a href="logout.php">Sign Out</a>
            <?php else: ?>
              <a href="login.php" class="<?= $page==='login' ? 'active' : '' ?>">Sign In</a>
              <a href="register.php" class="<?= $page==='register' ? 'active' : '' ?>">Register</a>
            <?php endif; ?>
        </div>
      </div>
    </nav>
    <main class="container">