<?php require_once __DIR__ . '/includes/functions.php';
$is_admin = maintenance_gate();

$accent = s('accent_color', '#F1592A');
$logo   = s('logo_text', 'Pikka');

$categories = get_work_categories();
$projects   = get_work_projects();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Our Work — <?= e(s('site_name', 'Pikka Creatives')) ?></title>
<meta name="description" content="A showcase of projects Pikka Creatives has delivered for New Zealand businesses — web development, logo design, SEO and more.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:ital,wght@0,400;0,600;0,700;1,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>:root{--accent:<?= e($accent) ?>}</style>
</head>
<body>

<!-- Preloader -->
<div id="preloader">
  <div class="loader-mark"></div>
  <div class="loader-word"><?= e($logo) ?><span>.</span></div>
</div>

<!-- Header -->
<header class="site-header">
  <div class="container nav">
    <a href="index.php" class="brand"><?= brand_mark($logo) ?></a>
    <nav class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="work.php">Work</a>
      <a href="contact.php">Contact</a>
    </nav>
    <a href="#" class="nav-cta" data-open-form>Start a project</a>
    <button class="burger" aria-label="Menu"><span></span><span></span><span></span></button>
  </div>
</header>

<span id="top"></span>

<!-- ===== Section 1: Hero ===== -->
<section class="hero no-stage">
  <div class="hero-grid-bg"></div>
  <div class="container">
    <span class="eyebrow center">Our Work</span>
    <h1>Projects we're proud of.</h1>
    <p class="hero-sub">A look at the branding, websites and campaigns we've built for New Zealand businesses — real work, for real Kiwi companies.</p>
  </div>
</section>

<!-- ===== Section 2: Filters + grid ===== -->
<section class="pad" id="work">
  <div class="container">

    <?php if ($categories): ?>
    <div class="work-filters">
      <button type="button" class="work-filter-btn active" data-work-filter="all">All</button>
      <?php foreach ($categories as $cat): ?>
        <button type="button" class="work-filter-btn" data-work-filter="<?= e($cat['slug']) ?>"><?= e($cat['name']) ?></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($projects): ?>
    <div class="work-grid">
      <?php foreach ($projects as $p): ?>
        <button type="button" class="work-card" data-work-item data-category="<?= e($p['category_slug'] ?? '') ?>"
                data-work-title="<?= e($p['title']) ?>"
                data-work-category="<?= e($p['category_name'] ?? '') ?>"
                data-work-desc="<?= e($p['description']) ?>"
                data-work-link="<?= e($p['link']) ?>">
          <div class="work-thumb">
            <?php if ($p['image']): ?>
              <img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>">
            <?php else: ?>
              <div class="img-placeholder" style="border-radius:0;border:0;aspect-ratio:4/5;height:100%">
                <span class="ph-ico">🖼</span>
                <span class="ph-label">Add image — in admin</span>
              </div>
            <?php endif; ?>
            <div class="work-thumb-overlay">
              <?php if ($p['category_name']): ?><span class="work-tag"><?= e($p['category_name']) ?></span><?php endif; ?>
              <h3><?= e($p['title']) ?></h3>
            </div>
          </div>
        </button>
      <?php endforeach; ?>
    </div>
    <p class="work-empty" data-work-empty>No projects in this category yet — check back soon.</p>
    <?php else: ?>
    <p class="work-empty" style="display:block">We're adding our latest work here shortly — check back soon.</p>
    <?php endif; ?>

  </div>
</section>

<!-- ===== Section 3: CTA ===== -->
<section class="cta-sec">
  <div class="container">
    <div class="cta-box">
      <span class="eyebrow center">Let's work together</span>
      <h2>Like what you see? Let's build yours.</h2>
      <p>Whether you're after a full brand refresh or a brand-new website, we'd love to hear about your project.</p>
      <div class="cta-actions">
        <a href="#" class="btn btn-primary" data-open-form>Start a project <span class="ico">↗</span></a>
        <a href="about.php" class="btn btn-outline">About us <span class="ico">→</span></a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/site-footer.php'; ?>

<!-- Project details (pop-up) -->
<div class="modal-overlay" id="workModal" aria-hidden="true">
  <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="workModalTitle">
    <button type="button" class="modal-close" data-close-work aria-label="Close">&times;</button>
    <span class="eyebrow" id="workModalCategory"></span>
    <h3 id="workModalTitle" style="margin:14px 0 12px"></h3>
    <p class="modal-sub" id="workModalDesc"></p>
    <a href="#" id="workModalLink" class="btn btn-primary" target="_blank" rel="noopener">View project <span class="ico">↗</span></a>
  </div>
</div>

<!-- Contact form (pop-up) -->
<div class="modal-overlay" id="contactModal" aria-hidden="true">
  <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <button type="button" class="modal-close" data-close-form aria-label="Close">&times;</button>
    <span class="eyebrow">Let's work together</span>
    <h3 id="modalTitle">Start a project</h3>
    <p class="modal-sub">Tell us a bit about what you need — we'll get back to you shortly.</p>
    <form class="contact-form" id="contactForm" novalidate>
      <div class="field row">
        <div><label>Name</label><input type="text" name="name" placeholder="Your name" required></div>
        <div><label>Email</label><input type="email" name="email" placeholder="Email address" required></div>
      </div>
      <div class="field"><label>Business name</label><input type="text" name="business" placeholder="Your business (optional)"></div>
      <div class="field">
        <label>What do you need?</label>
        <select name="need">
          <option value="">Select one</option>
          <option>Branding</option>
          <option>Web</option>
          <option>Social & Content</option>
          <option>Packaging & Print</option>
          <option>Not sure yet</option>
        </select>
      </div>
      <div class="field"><label>Tell us about your project</label><textarea name="message" rows="4" placeholder="Share a few details…" required></textarea></div>
      <button type="submit" class="btn btn-primary">Send message <span class="ico">→</span></button>
      <div class="form-msg" role="status"></div>
    </form>
  </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
