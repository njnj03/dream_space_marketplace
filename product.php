<?php $page='services'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<?php
  // Product data with detailed information
  $products = [
    201 => [
      "title" => "Property Valuation Service",
      "price" => "$299",
      "category" => "Valuation",
      "description" => "Professional property assessment and market analysis",
      "img" => "https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800",
      "details" => "Our certified appraisers provide comprehensive property valuations using the latest market data and industry standards. This service includes detailed market analysis, comparable sales research, and a professional valuation report that can be used for refinancing, selling, or investment decisions.",
      "features" => ["Certified appraiser assessment", "Market analysis report", "Comparable sales research", "Professional documentation", "30-day validity guarantee"]
    ],
    202 => [
      "title" => "Home Staging Consultation",
      "price" => "$199",
      "category" => "Staging",
      "description" => "Expert advice to maximize your property's appeal",
      "img" => "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800",
      "details" => "Transform your property into a buyer's dream with our professional staging consultation. Our certified staging experts will provide personalized recommendations to enhance your home's visual appeal and maximize its market value.",
      "features" => ["2-hour consultation", "Room-by-room assessment", "Furniture placement guide", "Color scheme recommendations", "Decluttering checklist"]
    ],
    203 => [
      "title" => "Real Estate Photography",
      "price" => "$150",
      "category" => "Photography",
      "description" => "Professional photos that showcase your property",
      "img" => "https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800",
      "details" => "High-quality photography is essential for attracting potential buyers. Our professional photographers use advanced equipment and techniques to capture your property in the best light, creating stunning images that make your listing stand out.",
      "features" => ["Professional photographer", "High-resolution images", "Virtual tour option", "Same-day delivery", "Unlimited usage rights"]
    ],
    204 => [
      "title" => "Market Analysis Report",
      "price" => "$99",
      "category" => "Analysis",
      "description" => "Comprehensive neighborhood and market trends analysis",
      "img" => "https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800",
      "details" => "Stay informed about your local real estate market with our detailed analysis reports. We track market trends, price movements, inventory levels, and neighborhood developments to help you make informed decisions.",
      "features" => ["Market trend analysis", "Price movement tracking", "Inventory level reports", "Neighborhood insights", "Future market predictions"]
    ],
    205 => [
      "title" => "Property Management",
      "price" => "$200/mo",
      "category" => "Management",
      "description" => "Complete property management and tenant services",
      "img" => "https://images.unsplash.com/photo-1560472354-b33ff0c44a43?q=80&w=800",
      "details" => "Let us handle all aspects of your rental property management. From tenant screening and rent collection to maintenance coordination and legal compliance, we provide comprehensive management services to maximize your investment returns.",
      "features" => ["Tenant screening & placement", "Rent collection", "Maintenance coordination", "Legal compliance", "Financial reporting"]
    ],
    206 => [
      "title" => "Legal Documentation",
      "price" => "$399",
      "category" => "Legal",
      "description" => "Contract preparation and legal document review",
      "img" => "https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=800",
      "details" => "Ensure your real estate transactions are legally sound with our professional legal documentation services. Our experienced real estate attorneys will prepare, review, and explain all necessary contracts and legal documents.",
      "features" => ["Contract preparation", "Document review", "Legal consultation", "Compliance checking", "Attorney support"]
    ],
    207 => [
      "title" => "Investment Consultation",
      "price" => "$250",
      "category" => "Investment",
      "description" => "Strategic advice for real estate investment opportunities",
      "img" => "https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=800",
      "details" => "Maximize your real estate investment potential with our expert consultation services. We analyze market opportunities, evaluate investment properties, and provide strategic advice tailored to your financial goals and risk tolerance.",
      "features" => ["Investment analysis", "ROI calculations", "Risk assessment", "Market opportunity identification", "Portfolio optimization"]
    ],
    208 => [
      "title" => "Home Inspection Service",
      "price" => "$350",
      "category" => "Inspection",
      "description" => "Thorough property inspection and detailed reporting",
      "img" => "https://images.unsplash.com/photo-1581578731548-c6a0c3f2fcc0?q=80&w=800",
      "details" => "Protect your investment with our comprehensive home inspection service. Our certified inspectors thoroughly examine all major systems and components of the property, providing detailed reports to help you make informed decisions.",
      "features" => ["Certified inspector", "Comprehensive inspection", "Detailed written report", "Photo documentation", "Follow-up consultation"]
    ],
    209 => [
      "title" => "Mortgage Brokerage",
      "price" => "$0",
      "category" => "Financing",
      "description" => "Connect with the best mortgage rates and lenders",
      "img" => "https://images.unsplash.com/photo-1554224154-26032ffc0d07?q=80&w=800",
      "details" => "Secure the best mortgage rates and terms with our extensive network of lenders. Our mortgage brokers work with multiple financial institutions to find the most competitive rates and loan products that fit your specific needs.",
      "features" => ["Multiple lender options", "Rate comparison", "Application assistance", "Pre-approval support", "No upfront fees"]
    ],
    210 => [
      "title" => "Relocation Assistance",
      "price" => "$199",
      "category" => "Relocation",
      "description" => "Complete moving and relocation support services",
      "img" => "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=800",
      "details" => "Make your move stress-free with our comprehensive relocation assistance. We help you find new homes, coordinate moving services, and provide local area information to ensure a smooth transition to your new location.",
      "features" => ["Area research", "Moving coordination", "Utility setup assistance", "Local service referrals", "Settlement support"]
    ]
  ];

  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if (!isset($products[$id])) { 
    echo '<p class="notice error">Product not found.</p>'; 
    include 'includes/footer.php'; 
    exit; 
  }
  
  $product = $products[$id];
  
  // Cookie tracking for recently visited products
  $cookie_name = 'recent_products';
  $recent_products = isset($_COOKIE[$cookie_name]) ? json_decode($_COOKIE[$cookie_name], true) : [];
  
  // Remove current product if it already exists
  $recent_products = array_filter($recent_products, function($item) use ($id) {
    return $item['id'] != $id;
  });
  
  // Add current product to the beginning
  array_unshift($recent_products, [
    'id' => $id,
    'title' => $product['title'],
    'price' => $product['price'],
    'category' => $product['category'],
    'img' => $product['img'],
    'visited_at' => time()
  ]);
  
  // Keep only the last 5 products
  $recent_products = array_slice($recent_products, 0, 5);
  
  // Set cookie with 30 days expiration
  setcookie($cookie_name, json_encode($recent_products), time() + (30 * 24 * 60 * 60), '/');
?>

<script>
// Optional: Add a visual indicator when a product is tracked
document.addEventListener('DOMContentLoaded', function() {
  // Show a subtle notification that the visit was tracked
  const notification = document.createElement('div');
  notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 12px 16px; border-radius: 8px; font-size: 14px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
  notification.textContent = 'Visit tracked! Check your recent visits.';
  document.body.appendChild(notification);
  
  // Remove notification after 3 seconds
  setTimeout(() => {
    notification.remove();
  }, 3000);
});
</script>

<h1><?= htmlspecialchars($product['title']) ?></h1>
<p class="lead"><strong><?= htmlspecialchars($product['price']) ?></strong> — <?= htmlspecialchars($product['category']) ?></p>

<div class="card" style="margin-bottom: 24px;">
  <img src="<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" style="width: 100%; height: 300px; object-fit: cover; border-radius: 12px; margin-bottom: 16px;">
  <h3>Service Description</h3>
  <p><?= htmlspecialchars($product['details']) ?></p>
</div>

<div class="card">
  <h3>What's Included</h3>
  <ul>
    <?php foreach ($product['features'] as $feature): ?>
      <li><?= htmlspecialchars($feature) ?></li>
    <?php endforeach; ?>
  </ul>
  <p style="margin-top: 24px;">
    <a class="btn" href="contacts.php">Contact Us</a>
    <a class="btn" href="listings.php" style="background: #374151; margin-left: 8px;">Back to Services</a>
  </p>
</div>

<div class="card" style="background: #f8fafc; border: 2px solid #e2e8f0;">
  <h3>Recently Visited Products</h3>
  <p>You've been tracking your product visits! <a href="recent.php">View your recent visits</a></p>
</div>

<?php include 'includes/footer.php'; ?>
