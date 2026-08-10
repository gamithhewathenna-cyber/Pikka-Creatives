<?php
require_once __DIR__ . '/auth.php';
require_login();
$page = $current_page ?? '';
$title = $page_title ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> — Pikka Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head><body>
<div class="wrap">
  <aside class="side">
    <div class="logo"><span class="dot">✳</span>Pikka<span style="color:var(--accent)">.</span></div>
    <?php $homeGroupPages = ['sections','services','why','process','stats','images'];
          $homeGroupActive = in_array($page, $homeGroupPages, true); ?>
    <nav>
      <a href="index.php" class="<?= $page==='dashboard'?'active':'' ?>">◧ Dashboard</a>
      <button type="button" class="grp-toggle<?= $homeGroupActive ? ' active' : '' ?>" data-grp-toggle="home">
        <span>Home Page Content</span>
        <span class="chev">▾</span>
      </button>
      <div class="grp-items" data-grp="home">
        <a href="sections.php" class="<?= $page==='sections'?'active':'' ?>">✎ Text & Sections</a>
        <a href="services.php" class="<?= $page==='services'?'active':'' ?>">▤ Services</a>
        <a href="why.php" class="<?= $page==='why'?'active':'' ?>">★ Why Choose Us</a>
        <a href="process.php" class="<?= $page==='process'?'active':'' ?>">➜ Process Steps</a>
        <a href="stats.php" class="<?= $page==='stats'?'active':'' ?>">▦ Stats Bar</a>
        <a href="images.php" class="<?= $page==='images'?'active':'' ?>">◑ Images</a>
      </div>
      <div class="grp">Site</div>
      <a href="settings.php" class="<?= $page==='settings'?'active':'' ?>">⚙ Settings</a>
      <a href="messages.php" class="<?= $page==='messages'?'active':'' ?>">✉ Messages</a>
      <a href="account.php" class="<?= $page==='account'?'active':'' ?>">◔ My Account</a>
      <a href="logout.php" class="logout">⏻ Log out</a>
    </nav>
  </aside>
  <script>
  (function () {
    var KEY = 'pikkaAdminHomeGrpOpen';
    var toggle = document.querySelector('[data-grp-toggle="home"]');
    var items = document.querySelector('[data-grp="home"]');
    if (!toggle || !items) return;
    var stored = localStorage.getItem(KEY);
    var open = toggle.classList.contains('active') || stored === '1' || stored === null;
    var setState = function (isOpen) {
      items.classList.toggle('open', isOpen);
      toggle.classList.toggle('open', isOpen);
    };
    setState(open);
    toggle.addEventListener('click', function () {
      var next = !items.classList.contains('open');
      setState(next);
      localStorage.setItem(KEY, next ? '1' : '0');
    });
  })();
  </script>
  <main class="main">
    <div class="topbar">
      <h1><?= e($title) ?></h1>
      <a class="view-site" href="../index.php" target="_blank">View site ↗</a>
    </div>
    <?php if ($f = flash()): ?><div class="flash"><?= e($f) ?></div><?php endif; ?>
