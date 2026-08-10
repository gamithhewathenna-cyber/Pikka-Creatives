<?php require_once __DIR__ . '/includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$is_admin = !empty($_SESSION['admin_id']);

if (s('maintenance_mode') === '1' && !$is_admin) {
    http_response_code(503);
    header('Retry-After: 3600');
    $accent = s('accent_color', '#F1592A');
    $logo   = s('logo_text', 'Pikka');
    $msg    = s('maintenance_message', "We're currently making some improvements. Please check back soon.");
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e(s('site_name', 'Pikka Creatives')) ?> — Under Maintenance</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{--accent:<?= e($accent) ?>}
  *{box-sizing:border-box}
  body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#0d0d0f;color:#f5f5f5;font-family:Inter,sans-serif;padding:24px;text-align:center}
  .mark{font-family:Sora,sans-serif;font-size:2rem;font-weight:700;margin-bottom:1.2rem}
  .mark span{color:var(--accent)}
  .card{max-width:480px}
  h1{font-family:Sora,sans-serif;font-size:1.6rem;margin:0 0 .8rem}
  p{color:#b8b8bd;line-height:1.6;margin:0;white-space:pre-line}
  .dot{color:var(--accent);margin-right:.4rem}
</style>
</head>
<body>
  <div class="card">
    <div class="mark"><span class="dot">✳</span><?= e($logo) ?><span>.</span></div>
    <h1>We'll be right back</h1>
    <p><?= e($msg) ?></p>
  </div>
</body>
</html>
<?php
    exit;
}

$accent = s('accent_color', '#F1592A');
$logo   = s('logo_text', 'Pikka');
$hero_img = c('hero_image');           // stored path if uploaded
$intro_img = c('intro_image');
$ind_img  = c('industries_image');
$marquee  = array_filter(array_map('trim', explode('|', s('marquee_items', ''))));
$tags     = array_filter(array_map('trim', explode('|', c('industries_tags', ''))));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e(s('site_name','Pikka Creatives')) ?> — Creative & Digital Solutions, New Zealand</title>
<meta name="description" content="<?= e(c('hero_subheadline')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:ital,wght@0,400;0,600;0,700;1,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>:root{--accent:<?= e($accent) ?>}</style>
</head>
<body>

<?php if ($is_admin && s('maintenance_mode') === '1'): ?>
<div style="position:fixed;top:0;left:0;right:0;z-index:9999;background:#111;color:#fff;text-align:center;font:600 13px/1 Inter,sans-serif;padding:10px">
  Maintenance mode is ON — visitors see the "under maintenance" page, only logged-in admins see this site. Turn it off in <a href="admin/settings.php" style="color:<?= e($accent) ?>">Settings</a> when you're ready to go live.
</div>
<?php endif; ?>

<!-- Preloader -->
<div id="preloader">
  <div class="loader-mark"></div>
  <div class="loader-word"><?= e($logo) ?><span>.</span></div>
</div>

<!-- Header -->
<header class="site-header">
  <div class="container nav">
    <a href="#top" class="brand"><span class="dot">✳</span><?= e($logo) ?><span class="accent">.</span></a>
    <nav class="nav-links">
      <a href="#top">Home</a>
      <a href="#services">Services</a>
      <a href="#why">Why Us</a>
      <a href="#process">Process</a>
      <a href="#industries">Industries</a>
    </nav>
    <a href="#contact" class="nav-cta" data-open-form>Start a project</a>
    <button class="burger" aria-label="Menu"><span></span><span></span><span></span></button>
  </div>
</header>

<span id="top"></span>

<!-- ===== Section 1: Hero ===== -->
<section class="hero">
  <div class="hero-grid-bg"></div>
  <div class="container">
    <span class="eyebrow center reveal"><?= e(c('hero_eyebrow')) ?></span>
    <h1 class="reveal d1"><?= nl2br(e(c('hero_headline'))) ?></h1>
    <p class="hero-sub reveal d2"><?= e(c('hero_subheadline')) ?></p>
    <div class="hero-actions reveal d3">
      <a href="#services" class="btn btn-primary"><?= e(c('hero_btn_primary')) ?> <span class="ico">↗</span></a>
      <a href="#contact" class="btn btn-outline" data-open-form><?= e(c('hero_btn_secondary')) ?> <span class="ico">→</span></a>
    </div>

    <div class="hero-stage reveal d2">
      <!-- floating quote -->
      <div class="float-card fc-quote">
        <span class="qmark">“</span>
        <p class="q"><?= e(c('hero_quote')) ?></p>
      </div>
      <!-- tags -->
      <div class="float-card fc-tags">
        <span class="tag-pill on">Brand & Identity</span>
        <span class="tag-pill">Web Design</span>
        <span class="tag-pill">SEO & Marketing</span>
        <span class="tag-pill on">Social & Content</span>
      </div>
      <!-- photo ring -->
      <div class="hero-photo-ring">
        <?php if ($hero_img): ?>
          <img src="<?= e($hero_img) ?>" alt="Pikka Creatives">
        <?php else: ?>
          <span class="ph">Add hero image<br>in admin</span>
        <?php endif; ?>
      </div>
      <!-- reviews -->
      <div class="float-card fc-reviews">
        <div>
          <div class="stars">★★★★★</div>
          <div class="r-stat"><?= e(c('hero_reviews_stat')) ?></div>
          <div class="r-label"><?= e(c('hero_reviews_label')) ?></div>
        </div>
      </div>
      <!-- social -->
      <div class="float-card fc-social">
        <a class="soc" href="#" aria-label="Facebook">f</a>
        <a class="soc" href="#" aria-label="Instagram">◎</a>
        <a class="soc" href="#" aria-label="LinkedIn">in</a>
      </div>
    </div>
  </div>
</section>

<!-- ===== Marquee ===== -->
<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
    <?php $loop = array_merge($marquee, $marquee);
    foreach ($loop as $m): ?><span class="marquee-item"><?= e($m) ?></span><?php endforeach; ?>
  </div>
</div>

<!-- ===== Section 2: Intro ===== -->
<section class="intro pad" id="intro">
  <div class="container intro-wrap">
    <div class="reveal">
      <span class="eyebrow"><?= e(c('intro_eyebrow')) ?></span>
      <h2 style="margin-top:16px"><?= e(c('intro_heading')) ?></h2>
    </div>
    <div class="intro-body reveal d1">
      <?php foreach (preg_split('/\n{2,}|(?<=\.)\s{2,}/', c('intro_body')) as $para):
        $para = trim($para); if ($para === '') continue; ?>
        <p><?= e($para) ?></p>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== Section 3: Services ===== -->
<section class="pad" id="services">
  <div class="container">
    <div class="svc-top">
      <div class="sec-head reveal">
        <span class="eyebrow"><?= e(c('services_eyebrow')) ?></span>
        <h2><?= e(c('services_heading')) ?></h2>
      </div>
      <p class="reveal d1" style="max-width:340px;color:var(--ink-2)"><?= e(c('services_intro')) ?></p>
    </div>
    <div class="svc-list">
      <?php $i = 0; foreach (get_rows('services') as $svc): $i++; ?>
        <div class="svc-item reveal">
          <div class="svc-head">
            <span class="svc-num"><?= sprintf('%02d', $i) ?></span>
            <span class="svc-title"><?= e($svc['title']) ?></span>
            <span class="svc-toggle">+</span>
          </div>
          <div class="svc-body"><div class="svc-body-inner"><?= e($svc['description']) ?></div></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="svc-cta reveal">
      <a href="#contact" class="btn btn-dark" data-open-form>Start your project <span class="ico">→</span></a>
    </div>
  </div>
</section>

<!-- ===== Section 4: Why Choose Us ===== -->
<section class="why pad" id="why">
  <div class="container why-wrap">
    <div class="why-intro reveal">
      <span class="eyebrow"><?= e(c('why_eyebrow')) ?></span>
      <h2 style="margin-top:16px"><?= e(c('why_heading')) ?></h2>
      <p><?= e(c('why_body')) ?></p>
    </div>
    <div class="why-grid reveal d1">
      <?php $n = 0; foreach (get_rows('why_reasons') as $r): $n++; ?>
        <div class="why-cell">
          <span class="n"><?= sprintf('0%d', $n) ?></span>
          <h4><?= e($r['title']) ?></h4>
          <p><?= e($r['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== Section 5: Process ===== -->
<section class="pad" id="process">
  <div class="container">
    <div class="sec-head center reveal">
      <span class="eyebrow center"><?= e(c('process_eyebrow')) ?></span>
      <h2><?= e(c('process_heading')) ?></h2>
      <p><?= e(c('process_intro')) ?></p>
    </div>
    <div class="proc-grid">
      <?php foreach (get_rows('process_steps') as $st): ?>
        <div class="proc-card reveal">
          <div class="line"></div>
          <div class="num"><?= e($st['step_number']) ?></div>
          <h4><?= e($st['title']) ?></h4>
          <p><?= e($st['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== Section 6: Industries ===== -->
<section class="ind pad" id="industries">
  <div class="container ind-wrap">
    <div class="ind-body reveal">
      <span class="eyebrow"><?= e(c('industries_eyebrow')) ?></span>
      <h2 style="margin:16px 0 16px"><?= e(c('industries_heading')) ?></h2>
      <p><?= e(c('industries_body')) ?></p>
    </div>
    <div class="reveal d1">
      <div class="ind-tags">
        <?php foreach ($tags as $t): ?><span class="ind-tag"><?= e($t) ?></span><?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ===== Section 7: Stats trust bar ===== -->
<section class="pad">
  <div class="container">
    <div class="stats-head reveal">
      <h2 style="font-size:clamp(1.8rem,3.6vw,2.6rem)"><?= e(c('stats_heading')) ?></h2>
    </div>
    <div class="stats-grid">
      <?php foreach (get_rows('stats') as $stt): ?>
        <div class="stat-card reveal">
          <span class="plus">✳</span>
          <h4><?= e($stt['title']) ?></h4>
          <p><?= e($stt['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== Section 8: Testimonial ===== -->
<section class="pad-sm">
  <div class="container testi reveal">
    <span class="eyebrow center"><?= e(c('testi_eyebrow')) ?></span>
    <p class="testi-quote"><span class="qm">“</span><?= e(c('testi_quote')) ?><span class="qm">”</span></p>
    <p class="testi-attr"><?= e(c('testi_attribution')) ?></p>
  </div>
</section>

<!-- ===== Section 9: CTA ===== -->
<section class="cta-sec" id="contact">
  <div class="container">
    <div class="cta-box reveal">
      <span class="eyebrow center"><?= e(c('cta_eyebrow')) ?></span>
      <h2><?= e(c('cta_heading')) ?></h2>
      <p><?= e(c('cta_body')) ?></p>
      <div class="cta-actions">
        <a href="#" class="btn btn-primary" data-open-form><?= e(c('cta_btn_primary')) ?> <span class="ico">↗</span></a>
        <a href="#services" class="btn btn-outline"><?= e(c('cta_btn_secondary')) ?> <span class="ico">→</span></a>
      </div>

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
</section>

<!-- Footer -->
<footer class="site-footer">
  <div class="container">
    <div class="foot-top">
      <a href="#top" class="foot-brand"><span class="dot">✳</span><?= e($logo) ?><span class="accent">.</span></a>
      <nav class="foot-nav">
        <a href="#services">Services</a><a href="#why">Why Us</a>
        <a href="#process">Process</a><a href="#industries">Industries</a>
        <a href="#contact" data-open-form>Contact</a>
      </nav>
    </div>
    <div class="foot-bottom">
      <span><?= e(s('footer_text')) ?></span>
      <span><?= e(s('email')) ?></span>
    </div>
  </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
