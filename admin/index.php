<?php
$current_page = 'dashboard'; $page_title = 'Dashboard';
require_once __DIR__ . '/header.php';

$counts = [];
foreach (['services','why_reasons','process_steps','stats','contact_messages'] as $t) {
    $r = mysqli_query(db(), "SELECT COUNT(*) c FROM `$t`");
    $counts[$t] = $r ? mysqli_fetch_assoc($r)['c'] : 0;
}
$unread = mysqli_fetch_assoc(mysqli_query(db(), "SELECT COUNT(*) c FROM contact_messages WHERE is_read=0"))['c'];
?>
<div class="card">
  <h2>Kia ora, <?= e($_SESSION['admin_name']) ?> 👋</h2>
  <p class="sub">Manage everything on the Pikka Creatives home page from <a href="home-content.php">Home Page Content</a> — use the tabs at the top to switch between sections.</p>
  <div class="two" style="grid-template-columns:repeat(3,1fr);gap:14px">
    <div class="row-item" style="margin:0"><div class="muted">Services</div><strong style="font-size:26px;font-family:Sora"><?= $counts['services'] ?></strong></div>
    <div class="row-item" style="margin:0"><div class="muted">Process steps</div><strong style="font-size:26px;font-family:Sora"><?= $counts['process_steps'] ?></strong></div>
    <div class="row-item" style="margin:0"><div class="muted">Trust stats</div><strong style="font-size:26px;font-family:Sora"><?= $counts['stats'] ?></strong></div>
  </div>
</div>

<div class="card">
  <h2>Messages</h2>
  <p class="sub">You have <strong><?= $unread ?></strong> unread enquiry<?= $unread==1?'':'ies' ?> out of <?= $counts['contact_messages'] ?> total.</p>
  <a class="btn btn-sm" href="messages.php">View messages →</a>
</div>

<div class="card">
  <h2>Quick links</h2>
  <p class="sub">Common edits</p>
  <div style="display:flex;gap:10px;flex-wrap:wrap">
    <a class="btn btn-ghost btn-sm" href="home-content.php?tab=sections">Edit section text</a>
    <a class="btn btn-ghost btn-sm" href="home-content.php?tab=images">Change images</a>
    <a class="btn btn-ghost btn-sm" href="home-content.php?tab=services">Edit services</a>
    <a class="btn btn-ghost btn-sm" href="settings.php">Logo & colour</a>
  </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
