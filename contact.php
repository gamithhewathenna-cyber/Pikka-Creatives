<?php require_once __DIR__ . '/includes/functions.php';
$is_admin = maintenance_gate();

$accent = s('accent_color', '#F1592A');
$logo   = s('logo_text', 'Pikka');
$email  = s('email', 'hello@pikkacreatives.co.nz');
$phone  = s('phone', '0212 724 724');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Contact Us — <?= e(s('site_name', 'Pikka Creatives')) ?></title>
<meta name="description" content="<?= e(c('contact_hero_lead', "Whether you've got a clear brief or just a spark of an idea, we'd love to hear from you. Tell us about your project and we'll get back to you within two business days.")) ?>">
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
      <a href="contact.php">Contact</a>
    </nav>
    <a href="#contact-form" class="nav-cta">Start a project</a>
    <button class="burger" aria-label="Menu"><span></span><span></span><span></span></button>
  </div>
</header>

<span id="top"></span>

<!-- ===== Section 1: Hero ===== -->
<section class="hero no-stage">
  <div class="hero-grid-bg"></div>
  <div class="container">
    <span class="eyebrow center"><?= e(c('contact_hero_eyebrow', 'Contact Us')) ?></span>
    <h1><?= e(c('contact_hero_headline', "Let's make something.")) ?></h1>
    <p class="hero-sub"><?= e(c('contact_hero_lead', "Whether you've got a clear brief or just a spark of an idea, we'd love to hear from you. Tell us about your project and we'll get back to you within two business days.")) ?></p>
  </div>
</section>

<!-- ===== Section 2: Form + details ===== -->
<section class="pad" id="contact-form">
  <div class="container contact-wrap">

    <div class="contact-card">
      <form id="contactForm" novalidate>
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
        <div class="field"><label>Tell us about your project</label><textarea name="message" rows="5" placeholder="Share a few details…" required></textarea></div>
        <button type="submit" class="btn btn-primary">Send message <span class="ico">→</span></button>
        <div class="form-msg" role="status"></div>
      </form>
    </div>

    <div class="details-card">
      <h3>Get in touch</h3>
      <div class="detail-row">
        <span class="ic">✉</span>
        <div>
          <div class="lbl">Email</div>
          <a class="val" href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
        </div>
      </div>
      <div class="detail-row">
        <span class="ic">☎</span>
        <div>
          <div class="lbl">Phone</div>
          <a class="val" href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a>
        </div>
      </div>
      <div class="detail-row">
        <span class="ic">📍</span>
        <div>
          <div class="lbl">Location</div>
          <span class="val"><?= e(c('contact_location', 'New Zealand — working with clients nationwide')) ?></span>
        </div>
      </div>
      <div class="social-row">
        <a class="soc" href="<?= e(c('contact_social_instagram', '#')) ?>" aria-label="Instagram">◎</a>
        <a class="soc" href="<?= e(c('contact_social_facebook', '#')) ?>" aria-label="Facebook">f</a>
        <a class="soc" href="<?= e(c('contact_social_linkedin', '#')) ?>" aria-label="LinkedIn">in</a>
      </div>
    </div>

  </div>

  <p class="contact-closing"><?= e(c('contact_closing', "Prefer a chat? Flick us an email and we'll set up a time. No pressure, no jargon — just a friendly kōrero about your ideas:")) ?> <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p>
</section>

<?php require __DIR__ . '/includes/site-footer.php'; ?>

<script src="assets/js/main.js"></script>
</body>
</html>
