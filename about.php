<?php require_once __DIR__ . '/includes/functions.php';
$is_admin = maintenance_gate();

$accent  = s('accent_color', '#F1592A');
$logo    = s('logo_text', 'Pikka');
$marquee = array_filter(array_map('trim', explode('|', s('marquee_items', ''))));

$values = [
    ['title' => 'People and place matter', 'desc' => 'We create with your business, your customers and the market in mind. The best work begins by understanding the people it needs to reach.'],
    ['title' => 'Honesty builds trust', 'desc' => 'Clear communication, fair pricing and no hidden surprises. We share our thinking openly and make sure you always understand what happens next.'],
    ['title' => 'Care lives in the details', 'desc' => 'The smallest choices can shape how people see and remember a brand. We give every detail the attention it deserves.'],
    ['title' => 'Created for the journey', 'desc' => 'We build flexible brands, websites and digital solutions that can evolve with your business and continue working well into the future.'],
];

$benefits = [
    ['title' => 'We understand your market', 'desc' => 'We take time to understand your business, your customers and what will connect with your New Zealand audience.'],
    ['title' => 'We work closely with you', 'desc' => 'Clear communication and genuine collaboration keep you involved and informed throughout the project.'],
    ['title' => 'Everything works together', 'desc' => 'Branding, websites, digital marketing, social content and print are shaped around one clear and consistent vision.'],
    ['title' => 'We think beyond today', 'desc' => 'We create flexible solutions that can evolve as your business, audience and ambitions grow.'],
];

$team = [
    ['role' => 'Founder & Creative Director'],
    ['role' => 'Designer'],
    ['role' => 'Web Developer'],
    ['role' => 'Content & Social'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>About Us — <?= e(s('site_name', 'Pikka Creatives')) ?></title>
<meta name="description" content="Pikka Creatives is a New Zealand-based design and content studio helping local brands look sharp, sound clear and stand out.">
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
    <a href="index.php" class="brand"><span class="dot">✳</span><?= e($logo) ?><span class="accent">.</span></a>
    <nav class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
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
    <span class="eyebrow center">About Pikka Creatives</span>
    <h1>A creative partner for New Zealand businesses with big ideas.</h1>
    <p class="hero-sub">Pikka Creative helps local brands look sharp, sound clear, and stand out — built on the belief that great design should belong to every business.</p>
  </div>
</section>

<!-- ===== Marquee ===== -->
<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
    <?php $loop = array_merge($marquee, $marquee);
    foreach ($loop as $m): ?><span class="marquee-item"><?= e($m) ?></span><?php endforeach; ?>
  </div>
</div>

<!-- ===== Image placeholders ===== -->
<section class="pad-sm">
  <div class="container img-duo">
    <div class="img-placeholder">
      <span class="ph-ico">🖼</span>
      <span class="ph-label">Add image — in admin</span>
    </div>
    <div class="img-placeholder">
      <span class="ph-ico">🖼</span>
      <span class="ph-label">Add image — in admin</span>
    </div>
  </div>
</section>

<!-- ===== Section 2: Who We Are ===== -->
<section class="intro pad" id="who-we-are">
  <div class="container intro-wrap">
    <div>
      <span class="eyebrow">Kia ora</span>
      <h2 style="margin-top:16px">A creative studio built for Aotearoa.</h2>
    </div>
    <div class="intro-body">
      <p>Pikka Creative is a New Zealand-based design and content studio on a mission to help local brands punch above their weight. We create branding, websites, social content, and packaging for businesses across Aotearoa — from small startups finding their feet to established names ready for a fresh look.</p>
      <p>Great ideas can begin anywhere, and they should never be limited by a postcode or the size of a budget. Pikka Creatives exists to give every New Zealand business access to design that feels thoughtful, confident and unmistakably its own. That belief is at the heart of everything we create.</p>
    </div>
  </div>
</section>

<!-- ===== Section 3: Our Story ===== -->
<section class="pad">
  <div class="container centered-copy">
    <span class="eyebrow center">Our story</span>
    <h2 style="margin:16px 0 20px">Where business understanding meets creative vision.</h2>
    <p>Every business has a story, but sometimes it takes a fresh perspective to see what makes it special. Priyal's understanding of New Zealand businesses, together with his long-standing passion for photography, brings a local eye for detail, people and the moments that make a brand feel real.</p>
    <p>That perspective comes together with Gamith and Creativelements' experience in creativity, design, web development and digital delivery. What began as a conversation between two people with different strengths became Pikka Creatives: a shared vision to bring thoughtful, high-quality creative work within reach of businesses across New Zealand.</p>
  </div>
</section>

<!-- ===== Section 4: What We Believe ===== -->
<section class="why pad">
  <div class="container why-wrap">
    <div class="why-intro">
      <span class="eyebrow">Our values</span>
      <h2 style="margin-top:16px">What guides everything we create.</h2>
    </div>
    <div class="why-grid">
      <?php foreach ($values as $v): ?>
        <div class="why-cell">
          <h4><?= e($v['title']) ?></h4>
          <p><?= e($v['desc']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== Section 5: How We Work ===== -->
<section class="intro pad">
  <div class="container centered-copy">
    <span class="eyebrow center">Our approach</span>
    <h2 style="margin:16px 0 20px">Collaborative, clear and refreshingly straightforward.</h2>
    <p>Good creative begins with a good conversation. From the first chat to final delivery, we keep communication open, updates clear and jargon out of the way. We listen first, create with purpose and refine the work together.</p>
    <p>You will have a creative partner in your corner who understands your goals, values your ideas and genuinely cares about helping your business grow.</p>
  </div>
</section>

<!-- ===== Section 6: Why Work With Us ===== -->
<section class="pad">
  <div class="container">
    <div class="sec-head center">
      <span class="eyebrow center">Why Pikka Creatives</span>
      <h2>Creative partnership you can rely on.</h2>
    </div>
    <div class="benefit-grid">
      <?php foreach ($benefits as $b): ?>
        <div class="benefit-card">
          <h4><?= e($b['title']) ?></h4>
          <p><?= e($b['desc']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== Section 7: Meet the Team ===== -->
<section class="pad" style="background:var(--mist)">
  <div class="container">
    <div class="sec-head center">
      <span class="eyebrow center">Our team</span>
      <h2>The people behind Pikka Creative.</h2>
      <p>A small, dedicated team of Kiwi creatives who care about doing things well. When you work with us, you work directly with the people making your ideas real.</p>
    </div>
    <div class="team-grid">
      <?php foreach ($team as $t): ?>
        <div class="team-card">
          <div class="team-photo">
            <span class="ph-ico">🖼</span>
            <span>Add photo</span>
          </div>
          <h4>[Add name]</h4>
          <span class="role"><?= e($t['role']) ?></span>
          <p class="bio">Add a short 1–2 line bio — background, what they do, a personal touch.</p>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="team-closing">Want to join the team? We're always keen to hear from talented Kiwi creatives — flick us an email at <a href="mailto:<?= e(s('email','hello@pikkacreatives.co.nz')) ?>"><?= e(s('email','hello@pikkacreatives.co.nz')) ?></a>.</p>
  </div>
</section>

<!-- ===== Section 8: CTA ===== -->
<section class="cta-sec" id="contact">
  <div class="container">
    <div class="cta-box">
      <span class="eyebrow center">Let's work together</span>
      <h2>Like how we think? Let's talk.</h2>
      <p>Kia ora — thanks for getting to know us. If you're a New Zealand business looking for a creative partner who genuinely gets your market, we'd love to hear from you.</p>
      <div class="cta-actions">
        <a href="#" class="btn btn-primary" data-open-form>Start a project <span class="ico">↗</span></a>
        <a href="index.php#services" class="btn btn-outline">View our work <span class="ico">→</span></a>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="site-footer">
  <div class="container">
    <div class="foot-top">
      <a href="index.php" class="foot-brand"><span class="dot">✳</span><?= e($logo) ?><span class="accent">.</span></a>
      <nav class="foot-nav">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
      </nav>
    </div>
    <div class="foot-bottom">
      <span><?= e(s('footer_text')) ?></span>
      <span><?= e(s('email')) ?></span>
    </div>
  </div>
</footer>

<!-- Contact form (pop-up) -->
<div class="modal-overlay" id="contactModal" aria-hidden="true">
  <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <button type="button" class="modal-close" data-close-form aria-label="Close">&times;</button>
    <span class="eyebrow">Let's work together</span>
    <h3 id="modalTitle">Start a project</h3>
    <p class="modal-sub">Tell us a bit about what you need — we'll get back to you shortly.</p>
    <form class="contact-form" id="contactForm" novalidate>
      <div class="row">
        <input type="text" name="name" placeholder="Your name" required>
        <input type="email" name="email" placeholder="Email address" required>
      </div>
      <textarea name="message" rows="4" placeholder="Tell us about your project…" required></textarea>
      <button type="submit" class="btn btn-primary">Send message <span class="ico">→</span></button>
      <div class="form-msg" role="status"></div>
    </form>
  </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
