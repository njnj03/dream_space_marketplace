<?php $page='about'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<h1>About <?= htmlspecialchars($companyName) ?></h1>
<p class="lead">We’re a modern real estate company focused on clarity, service, and results. Our agents bring deep local expertise and a commitment to honest guidance.</p>
<div class="grid cols-3">
  <div class="card">
    <h3>Our Philosophy</h3>
    <p>Real estate should be transparent and empowering—no surprises, just great homes.</p>
  </div>
  <div class="card">
    <h3>Our Services</h3>
    <p>Buying, selling, renting, and commercial leasing—all with tailored strategies.</p>
  </div>
  <div class="card">
    <h3>Our Team</h3>
    <p>Licensed agents and market analysts who care about your goals.</p>
  </div>
</div>
<?php include 'includes/footer.php'; ?>