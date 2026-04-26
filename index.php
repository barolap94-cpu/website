<?php
require_once 'config.php';
$pageTitle = 'Home — ChimeWorks Creamery';
$adminPreview = isset($_GET['admin_preview']);
$products  = $pdo->query("SELECT * FROM products WHERE is_active=1 ORDER BY RAND() LIMIT 8")->fetchAll();
$offers    = $pdo->query("SELECT * FROM special_offers WHERE is_active=1 ORDER BY discount_pct DESC LIMIT 3")->fetchAll();
$emojis    = ['🍦','🍧','🍨','🧁','🍡','🎂','🧊','🍮'];
include 'includes/head.php';
if (!$adminPreview) include 'includes/navbar.php';
?>
<?php if ($adminPreview): ?>
<div class="admin-preview-page">
  <div class="container-fluid py-4 px-3">
<?php else: ?>
<div class="page-wrapper">
  <div class="container py-4 flex-grow-1">
    <?php showFlash(); ?>
<?php endif; ?>

    <!-- Hero -->
    <div class="hero-section">
      <span class="hero-emoji">🍦</span>
      <h1 style="font-family:'Poppins',sans-serif;font-weight:900;font-size:clamp(1.8rem,5vw,3.5rem);margin-top:1rem;line-height:1.15">Every Scoop Rings with Pure Joy</h1>
      <p style="font-size:clamp(1rem,2.5vw,1.2rem);opacity:.92;max-width:540px;margin:1rem auto 0">
        Handcrafted premium ice cream made with the finest natural ingredients. Customise your perfect scoop and have it delivered fresh to your door.
      </p>
      <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
        <a href="menu.php" class="btn btn-lg px-5 fw-800" style="background:#fff;color:var(--primary);border-radius:50px"><i class="bi bi-grid-3x3-gap-fill"></i> Browse Menu</a>
        <a href="offers.php" class="btn btn-lg px-5 fw-800" style="background:rgba(255,255,255,.2);color:#fff;border:2px solid rgba(255,255,255,.5);border-radius:50px"><i class="bi bi-tag-heart-fill"></i> View Offers</a>
      </div>
      <?php if (!isLoggedIn()): ?>
        <p style="margin-top:1.5rem;opacity:.8;font-size:.9rem">
          <a href="login.php" style="color:#fff;font-weight:800"><i class="bi bi-box-arrow-in-right"></i> Sign in</a> or
          <a href="register.php" style="color:#fff;font-weight:800"><i class="bi bi-person-plus-fill"></i> create an account</a> to order.
        </p>
      <?php endif; ?>
    </div>

    <!-- Features -->
    <div class="row g-3 mb-4">
      <?php foreach([['bi-leaf-fill','All-Natural Ingredients','No artificial colours or preservatives. Real, fresh ingredients only.'],['bi-truck-front-fill','Fast Delivery','Fresh scoops delivered within 30 to 45 minutes.'],['bi-palette-fill','Fully Customisable','Choose your flavours, scoops, size, and toppings your way.'],['bi-trophy-fill','Award-Winning','Best Ice Cream in Metro Manila — 2024 Food & Beverage Awards.']] as $f): ?>
        <div class="col-6 col-md-3">
          <div class="cw-card p-3 h-100 text-center">
            <i class="bi <?= $f[0] ?>" style="font-size:2rem;color:var(--primary)"></i>
            <div class="fw-800 mt-2" style="font-size:.92rem"><?= $f[1] ?></div>
            <div class="mt-1" style="color:var(--text-muted);font-size:.78rem;line-height:1.5"><?= $f[2] ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Offers -->
    <?php if ($offers): ?>
      <h2 class="section-title"><i class="bi bi-tag-heart-fill" style="color:var(--primary)"></i> Special Offers</h2>
      <p class="section-subtitle">Limited-time promotions — do not miss out</p>
      <div class="row g-4 mb-5">
        <?php foreach($offers as $o): ?>
          <div class="col-md-4">
            <div class="offer-card h-100">
              <div class="offer-badge"><i class="bi bi-fire"></i> <?= $o['discount_pct'] ?>% OFF</div>
              <h5 class="fw-800 mt-1 mb-2"><?= clean($o['title']) ?></h5>
              <p class="mb-3" style="opacity:.92;font-size:.92rem;line-height:1.6"><?= clean($o['description']) ?></p>
              <p class="small mb-3" style="opacity:.75"><i class="bi bi-calendar-event"></i> Valid until <?= date('F d, Y',strtotime($o['valid_until'])) ?></p>
              <a href="menu.php" class="btn d-flex align-items-center justify-content-center gap-2" style="background:rgba(255,255,255,.2);color:#fff;font-weight:800;border:2px solid rgba(255,255,255,.5);border-radius:50px"><i class="bi bi-basket3-fill"></i> Order Now</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Featured products -->
    <h2 class="section-title"><i class="bi bi-stars" style="color:var(--primary)"></i> Today's Featured Flavours</h2>
    <p class="section-subtitle">Crafted fresh every morning — order yours before they sell out</p>
    <div class="row g-4 mb-4">
      <?php foreach($products as $i=>$p): ?>
        <div class="col-6 col-md-3">
          <div class="cw-card h-100">
            <div class="product-img-wrap" style="height:150px;border-radius:var(--radius) var(--radius) 0 0">
              <?php if(!empty($p['image_path'])&&file_exists(__DIR__.'/'.$p['image_path'])): ?>
                <img src="<?= clean($p['image_path']) ?>" alt="<?= clean($p['name']) ?>">
              <?php else: ?><span><?= $emojis[$i%count($emojis)] ?></span><?php endif; ?>
            </div>
            <div class="p-3">
              <span class="product-cat"><?= clean($p['category']) ?></span>
              <div class="product-name mt-1"><?= clean($p['name']) ?></div>
              <p class="small mt-1 mb-2" style="color:var(--text-muted);font-size:.79rem;line-height:1.4"><?= clean($p['description']??'') ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="product-price"><?= peso($p['base_price']) ?></span>
                <a href="customize.php?product=<?= $p['id'] ?>" class="btn btn-cw btn-sm"><i class="bi bi-basket3-fill"></i> Order</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mb-3">
      <a href="menu.php" class="btn btn-cw px-5"><i class="bi bi-grid-3x3-gap-fill"></i> View Full Menu</a>
    </div>
  </div>
</div>

<?php if (!$adminPreview) include 'includes/footer.php'; ?>
