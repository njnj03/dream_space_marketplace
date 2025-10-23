<?php $page='services'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>

<?php
  // Get recently visited products from cookie
  $cookie_name = 'recent_products';
  $recent_products = isset($_COOKIE[$cookie_name]) ? json_decode($_COOKIE[$cookie_name], true) : [];
  
  // Product data for display
  $all_products = [
    201 => ["Property Valuation Service", "$299", "Valuation"],
    202 => ["Home Staging Consultation", "$199", "Staging"],
    203 => ["Real Estate Photography", "$150", "Photography"],
    204 => ["Market Analysis Report", "$99", "Analysis"],
    205 => ["Property Management", "$200/mo", "Management"],
    206 => ["Legal Documentation", "$399", "Legal"],
    207 => ["Investment Consultation", "$250", "Investment"],
    208 => ["Home Inspection Service", "$350", "Inspection"],
    209 => ["Mortgage Brokerage", "$0", "Financing"],
    210 => ["Relocation Assistance", "$199", "Relocation"]
  ];
  
  // Get image URLs for products
  $product_images = [
    201 => "https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800",
    202 => "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800",
    203 => "https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800",
    204 => "https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800",
    205 => "https://images.unsplash.com/photo-1560472354-b33ff0c44a43?q=80&w=800",
    206 => "https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=800",
    207 => "https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=800",
    208 => "https://images.unsplash.com/photo-1581578731548-c6a0c3f2fcc0?q=80&w=800",
    209 => "https://images.unsplash.com/photo-1554224154-26032ffc0d07?q=80&w=800",
    210 => "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=800"
  ];
?>

<h1>Recently Visited Products</h1>
<p class="lead">Here are the last 5 products and services you've viewed on our website.</p>

<?php if (empty($recent_products)): ?>
  <div class="card">
    <h3>No Recent Visits</h3>
    <p>You haven't visited any product pages yet. <a href="listings.php">Browse our services</a> to get started!</p>
  </div>
<?php else: ?>
  <div class="grid">
    <?php foreach ($recent_products as $index => $product): ?>
      <?php if (isset($all_products[$product['id']])): ?>
        <div class="card property product-card">
          <img src="<?= htmlspecialchars($product_images[$product['id']]) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
          <div>
            <h3><?= htmlspecialchars($product['title']) ?></h3>
            <p><strong><?= htmlspecialchars($product['price']) ?></strong> — <?= htmlspecialchars($product['category']) ?></p>
            <p class="kicker">Visited <?= date('M j, Y \a\t g:i A', $product['visited_at']) ?></p>
            <p><a class="btn" href="product.php?id=<?= (int)$product['id'] ?>">View Again</a></p>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<div class="card" style="margin-top: 24px; background: #f8fafc; border: 2px solid #e2e8f0;">
  <h3>Cookie Information</h3>
  <p>We use cookies to track your recently visited products to provide you with a better browsing experience. This helps us understand which services interest you most and allows you to quickly return to products you've viewed.</p>
  <p><strong>Cookie Name:</strong> recent_products</p>
  <p><strong>Expiration:</strong> 30 days</p>
  <p><strong>Data Stored:</strong> Product ID, title, price, category, image URL, and visit timestamp</p>
</div>

<div style="margin-top: 24px;">
  <a class="btn" href="listings.php">Back to All Services</a>
  <a class="btn" href="index.php" style="background: #374151; margin-left: 8px;">Home</a>
</div>

<?php include 'includes/footer.php'; ?>
