<?php
/* Shared 4-column site footer. Expects $logo (site logo text) in scope, and
   optionally $footer_brand_href (defaults to 'index.php') and $services
   (defaults to a fresh get_rows('services') call). Included on every
   public-facing page so the footer stays identical everywhere. */
$footer_brand_href = $footer_brand_href ?? 'index.php';
$services = $services ?? get_rows('services');
?>
<!-- Footer -->
<footer class="site-footer">
  <div class="container">
    <div class="foot-grid">
      <div class="foot-col foot-about">
        <a href="<?= e($footer_brand_href) ?>" class="foot-brand"><?= brand_mark($logo, true) ?></a>
        <p class="foot-desc"><?= e(s('footer_text')) ?></p>
        <div class="foot-social">
          <a class="soc" href="<?= e(c('contact_social_instagram', '#')) ?>" aria-label="Instagram">◎</a>
          <a class="soc" href="<?= e(c('contact_social_facebook', '#')) ?>" aria-label="Facebook">f</a>
          <a class="soc" href="<?= e(c('contact_social_linkedin', '#')) ?>" aria-label="LinkedIn">in</a>
        </div>
      </div>
      <div class="foot-col">
        <h4>Quick Links</h4>
        <nav class="foot-nav">
          <a href="index.php">Home</a>
          <a href="about.php">About</a>
          <a href="work.php">Work</a>
          <a href="contact.php">Contact</a>
        </nav>
      </div>
      <div class="foot-col">
        <h4>Services</h4>
        <div class="foot-list">
          <?php if ($services): foreach ($services as $svc): ?>
            <span><?= e($svc['title']) ?></span>
          <?php endforeach; else: ?>
            <span>Our services</span>
          <?php endif; ?>
        </div>
      </div>
      <div class="foot-col">
        <h4>Get in Touch</h4>
        <ul class="foot-contact">
          <li><?= e(c('contact_location', 'New Zealand — working with clients nationwide')) ?></li>
          <li><a href="tel:<?= e(preg_replace('/\s+/', '', s('phone'))) ?>"><?= e(s('phone')) ?></a></li>
          <li><a href="mailto:<?= e(s('email')) ?>"><?= e(s('email')) ?></a></li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© <?= date('Y') ?> <?= e(s('site_name', 'Pikka Creatives')) ?>. All rights reserved.</span>
    </div>
  </div>
</footer>
