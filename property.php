<?php $page='services'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<?php
  $all = [
    101 => ["Sunny 2BR Condo","Riverview","$495,000","2 bed • 2 bath • 1,050 sqft"],
    102 => ["Modern Family Home","Cedar Park","$789,000","4 bed • 3 bath • 2,300 sqft"],
    103 => ["Downtown Loft","Central","$2,450/mo","1 bed • 1 bath • 820 sqft"],
    104 => ["Retail Corner Unit","Market Square","$3,900/mo","1,400 sqft • high foot traffic"],
    105 => ["Cozy Studio","Greenwood","$1,600/mo","Studio • 1 bath • 520 sqft"],
  ];
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if (!isset($all[$id])) { echo '<p class="notice error">Property not found.</p>'; include 'includes/footer.php'; exit; }
  [$title,$hood,$price,$facts] = $all[$id];
?>
<h1><?= htmlspecialchars($title) ?></h1>
<p class="lead"><strong><?= htmlspecialchars($price) ?></strong> — <?= htmlspecialchars($hood) ?></p>
<div class="card">
  <h3>Key facts</h3>
  <p><?= htmlspecialchars($facts) ?></p>
  <p class="kicker">Marketplace hooks (for later):</p>
  <ul>
    <li>On page load, log visit: <code>track_visit($user_id, 'dreamspace', <?= (int)$id ?>)</code></li>
    <li>Show review form and average rating for this property.</li>
  </ul>
</div>
<?php include 'includes/footer.php'; ?>