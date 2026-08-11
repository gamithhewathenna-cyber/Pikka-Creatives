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
    <nav>
      <a href="index.php" class="<?= $page==='dashboard'?'active':'' ?>"><span class="nav-ic">◧</span>Dashboard</a>
      <a href="home-content.php" class="<?= $page==='home-content'?'active':'' ?>"><span class="nav-ic">⌂</span>Home Page Content</a>
      <?php $pcTab = $_GET['tab'] ?? 'about'; ?>
      <a href="page-content.php?tab=about" class="<?= $page==='page-content' && $pcTab==='about' ? 'active' : '' ?>"><span class="nav-ic">◈</span>About Us</a>
      <a href="page-content.php?tab=contact" class="<?= $page==='page-content' && $pcTab==='contact' ? 'active' : '' ?>"><span class="nav-ic">☎</span>Contact Us</a>
      <a href="work.php" class="<?= $page==='work'?'active':'' ?>"><span class="nav-ic">▤</span>Our Work</a>
      <div class="grp">Site</div>
      <a href="messages.php" class="<?= $page==='messages'?'active':'' ?>"><span class="nav-ic">✉</span>Messages</a>
      <?php if (is_admin_role()): ?>
      <a href="settings.php" class="<?= $page==='settings'?'active':'' ?>"><span class="nav-ic">⚙</span>Settings</a>
      <a href="users.php" class="<?= $page==='users'?'active':'' ?>"><span class="nav-ic">◉</span>Users</a>
      <?php endif; ?>
      <a href="account.php" class="<?= $page==='account'?'active':'' ?>"><span class="nav-ic">◔</span>My Account</a>
      <a href="logout.php" class="logout"><span class="nav-ic">⏻</span>Log out</a>
    </nav>
  </aside>
  <main class="main">
    <div class="topbar">
      <h1><?= e($title) ?></h1>
      <a class="view-site" href="../index.php" target="_blank">View site ↗</a>
    </div>
    <?php if ($f = flash()): ?><div class="flash"><?= e($f) ?></div><?php endif; ?>
