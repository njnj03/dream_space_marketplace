<?php $page='services'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<?php
  $products = [
    ["id"=>201, "title"=>"Property Valuation Service", "price"=>"$299", "category"=>"Valuation", "description"=>"Professional property assessment and market analysis", "img"=>"https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800"],
    ["id"=>202, "title"=>"Home Staging Consultation", "price"=>"$199", "category"=>"Staging", "description"=>"Expert advice to maximize your property's appeal", "img"=>"https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800"],
    ["id"=>203, "title"=>"Real Estate Photography", "price"=>"$150", "category"=>"Photography", "description"=>"Professional photos that showcase your property", "img"=>"https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800"],
    ["id"=>204, "title"=>"Market Analysis Report", "price"=>"$99", "category"=>"Analysis", "description"=>"Comprehensive neighborhood and market trends analysis", "img"=>"https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800"],
    ["id"=>205, "title"=>"Property Management", "price"=>"$200/mo", "category"=>"Management", "description"=>"Complete property management and tenant services", "img"=>"https://images.unsplash.com/photo-1560472354-b33ff0c44a43?q=80&w=800"],
    ["id"=>206, "title"=>"Legal Documentation", "price"=>"$399", "category"=>"Legal", "description"=>"Contract preparation and legal document review", "img"=>"https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=800"],
    ["id"=>207, "title"=>"Investment Consultation", "price"=>"$250", "category"=>"Investment", "description"=>"Strategic advice for real estate investment opportunities", "img"=>"https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=800"],
    ["id"=>208, "title"=>"Home Inspection Service", "price"=>"$350", "category"=>"Inspection", "description"=>"Thorough property inspection and detailed reporting", "img"=>"https://images.unsplash.com/photo-1581578731548-c6a0c3f2fcc0?q=80&w=800"],
    ["id"=>209, "title"=>"Mortgage Brokerage", "price"=>"$0", "category"=>"Financing", "description"=>"Connect with the best mortgage rates and lenders", "img"=>"https://images.unsplash.com/photo-1554224154-26032ffc0d07?q=80&w=800"],
    ["id"=>210, "title"=>"Relocation Assistance", "price"=>"$199", "category"=>"Relocation", "description"=>"Complete moving and relocation support services", "img"=>"https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=800"],
  ];
?>
<h1>Our Products & Services</h1>
<p class="lead">Discover our comprehensive range of real estate services designed to meet all your property needs.</p>

<div class="card" style="margin-bottom: 24px; background: #f8fafc; border: 2px solid #e2e8f0;">
  <h3>Recently Visited Products</h3>
  <p>View your last 5 visited products and services.</p>
  <a class="btn" href="recent.php" style="background: #374151;">View Recent Visits</a>
</div>

<div class="grid">
<?php foreach ($products as $p): ?>
  <div class="card property product-card">
    <img src="<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
    <div>
      <h3><?= htmlspecialchars($p['title']) ?></h3>
      <p><strong><?= htmlspecialchars($p['price']) ?></strong> — <?= htmlspecialchars($p['category']) ?></p>
      <p><?= htmlspecialchars($p['description']) ?></p>
      <p><a class="btn" href="product.php?id=<?= (int)$p['id'] ?>">View details</a></p>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include 'includes/footer.php'; ?>