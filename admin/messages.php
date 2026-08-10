<?php
$current_page = 'messages'; $page_title = 'Messages';
require_once __DIR__ . '/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $id = (int)($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'delete') {
        $st = mysqli_prepare(db(), "DELETE FROM contact_messages WHERE id=?");
        mysqli_stmt_bind_param($st, 'i', $id); mysqli_stmt_execute($st);
        flash('Message deleted.');
    } elseif (($_POST['action'] ?? '') === 'read') {
        $st = mysqli_prepare(db(), "UPDATE contact_messages SET is_read=1 WHERE id=?");
        mysqli_stmt_bind_param($st, 'i', $id); mysqli_stmt_execute($st);
    }
    header('Location: messages.php'); exit;
}

$rows = [];
$r = mysqli_query(db(), "SELECT * FROM contact_messages ORDER BY created_at DESC");
while ($x = mysqli_fetch_assoc($r)) $rows[] = $x;

require __DIR__ . '/header.php';
?>
<div class="card">
  <h2>Enquiries</h2>
  <p class="sub">Messages sent through the home page contact form.</p>
  <?php if (!$rows): ?>
    <p class="muted">No messages yet.</p>
  <?php else: foreach ($rows as $m): ?>
    <div class="msg-row">
      <div class="meta">
        <span><strong><?= e($m['name']) ?></strong> · <a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a></span>
        <span><?= $m['is_read'] ? '' : '<span class="unread">● new</span> ' ?><?= e(date('j M Y, g:ia', strtotime($m['created_at']))) ?></span>
      </div>
      <p style="margin:6px 0 10px"><?= nl2br(e($m['message'])) ?></p>
      <div style="display:flex;gap:8px">
        <?php if (!$m['is_read']): ?>
        <form method="post" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="read"><input type="hidden" name="id" value="<?= $m['id'] ?>"><button class="btn btn-ghost btn-sm">Mark read</button></form>
        <?php endif; ?>
        <form method="post" style="display:inline" onsubmit="return confirm('Delete this message?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $m['id'] ?>"><button class="btn btn-danger btn-sm">Delete</button></form>
      </div>
    </div>
  <?php endforeach; endif; ?>
</div>
<?php require __DIR__ . '/footer.php'; ?>
