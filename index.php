<?php $page='home'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<?php
// auth.php is already included in header.php
$isLoggedIn = is_user_logged_in();
$userName = $_SESSION['user_name'] ?? '';
?>
<?php if ($isLoggedIn): ?>
  <div class="card" style="background: #dbeafe; border: 2px solid #93c5fd; margin-bottom: 24px;">
    <h3>Welcome back, <?= htmlspecialchars($userName) ?>!</h3>
    <p>You're signed in. <a href="account.php">View your account</a> or <a href="listings.php">browse our services</a>.</p>
  </div>
<?php endif; ?>
<section class="hero">
  <div class="wrap">
    <div>
      <h1>Find your next home with confidence.</h1>
      <p class="lead">Browse curated listings, compare neighborhoods, and connect with expert agents at DreamSpace Realty.</p>
      <p>
  <a class="btn" href="listings.php">Explore Services</a>
  <a class="btn" href="properties.php" style="background:#059669;margin-left:0.5rem;">View Properties</a>
</p>

    </div>
    <div class="card">
      <h2>Why DreamSpace?</h2>
      <ul>
        <li>Vetted listings and transparent pricing</li>
        <li>Neighborhood insights and school data</li>
        <li>Friendly agents guiding you end-to-end</li>
      </ul>
    </div>
  </div>
</section>
<section class="grid cols-3">
  <div class="card">
    <span class="badge">Buy</span>
    <h3>Homes for sale</h3>
    <p class="lead">Condos, townhomes, and single-family houses across the city.</p>
    <a href="properties.php" class="btn" style="margin-top: 12px; font-size: 14px; padding: 8px 12px;">View Properties</a>
  </div>
  <div class="card">
    <span class="badge">Services</span>
    <h3>Real Estate Services</h3>
    <p class="lead">Professional services including valuation, staging, and management.</p>
    <a href="listings.php" class="btn" style="margin-top: 12px; font-size: 14px; padding: 8px 12px;">View Services</a>
  </div>
  <div class="card">
    <span class="badge">Rent</span>
    <h3>Apartments & studios</h3>
    <p class="lead">Flexible leases in great locations and amenities.</p>
    <a href="properties.php" class="btn" style="margin-top: 12px; font-size: 14px; padding: 8px 12px;">View Rentals</a>
  </div>
</section>
<?php include 'includes/footer.php'; ?>