<?php $page='properties'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<?php
  $properties = [
    ["id"=>101, "title"=>"Sunny 2BR Condo", "price"=>"$495,000", "neighborhood"=>"Riverview", "type"=>"Condo", "img"=>"https://images.unsplash.com/photo-1505691723518-36a5ac3b2d52?q=80&w=800"],
    ["id"=>102, "title"=>"Modern Family Home", "price"=>"$789,000", "neighborhood"=>"Cedar Park", "type"=>"House", "img"=>"https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800"],
    ["id"=>103, "title"=>"Downtown Loft", "price"=>"$2,450/mo", "neighborhood"=>"Central", "type"=>"Apartment", "img"=>"https://images.unsplash.com/photo-1484154218962-a197022b5858?q=80&w=800"],
    ["id"=>104, "title"=>"Retail Corner Unit", "price"=>"$3,900/mo", "neighborhood"=>"Market Square", "type"=>"Retail", "img"=>"https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=800"],
    ["id"=>105, "title"=>"Cozy Studio", "price"=>"$1,600/mo", "neighborhood"=>"Greenwood", "type"=>"Studio", "img"=>"https://images.unsplash.com/photo-1434434312917-fff2b7d5b8df?q=80&w=800"],
    ["id"=>106, "title"=>"Luxury Penthouse", "price"=>"$1,200,000", "neighborhood"=>"Downtown", "type"=>"Penthouse", "img"=>"https://images.unsplash.com/photo-1512917770250-7d763102981b?q=80&w=800"],
    ["id"=>107, "title"=>"Suburban Villa", "price"=>"$650,000", "neighborhood"=>"Oak Hills", "type"=>"Villa", "img"=>"https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=800"],
    ["id"=>108, "title"=>"Waterfront Condo", "price"=>"$850,000", "neighborhood"=>"Harbor View", "type"=>"Condo", "img"=>"https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=800"],
  ];
?>
<h1>Featured Properties</h1>
<p class="lead">Browse our curated selection of premium properties. Each listing has been carefully vetted for quality and value.</p>
<div class="grid">
<?php foreach ($properties as $p): ?>
  <div class="card property product-card">
    <img src="<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
    <div>
      <h3><?= htmlspecialchars($p['title']) ?></h3>
      <p><strong><?= htmlspecialchars($p['price']) ?></strong> — <?= htmlspecialchars($p['type']) ?> in <?= htmlspecialchars($p['neighborhood']) ?></p>
      <p><a class="btn" href="property.php?id=<?= (int)$p['id'] ?>">View details</a></p>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include 'includes/footer.php'; ?>
