<?php
$current_page = 'sections'; $page_title = 'Text & Sections';
require_once __DIR__ . '/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $fields = $_POST['field'] ?? [];
    $stmt = mysqli_prepare(db(), "UPDATE page_content SET content_value = ? WHERE content_key = ?");
    foreach ($fields as $key => $val) {
        $val = (string)$val;
        mysqli_stmt_bind_param($stmt, 'ss', $val, $key);
        mysqli_stmt_execute($stmt);
    }
    flash('Section text updated successfully.');
    header('Location: sections.php'); exit;
}

// Fetch grouped, in insertion order. Skip *_image keys (handled on Images page).
$res = mysqli_query(db(), "SELECT * FROM page_content WHERE content_key NOT LIKE '%_image' ORDER BY id ASC");
$groups = [];
while ($row = mysqli_fetch_assoc($res)) $groups[$row['section']][] = $row;

require __DIR__ . '/header.php';
?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <?php foreach ($groups as $section => $rows): ?>
    <div class="card">
      <h2><?= e($section) ?> section</h2>
      <p class="sub">Edit the copy for this part of the home page.</p>
      <?php foreach ($rows as $r):
        $isArea = ($r['field_type'] === 'textarea'); ?>
        <div class="field">
          <label><?= e($r['label']) ?></label>
          <?php if ($isArea): ?>
            <textarea name="field[<?= e($r['content_key']) ?>]" rows="3"><?= e($r['content_value']) ?></textarea>
          <?php else: ?>
            <input type="text" name="field[<?= e($r['content_key']) ?>]" value="<?= e($r['content_value']) ?>">
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
  <button class="btn" type="submit">Save all changes</button>
</form>
<?php require __DIR__ . '/footer.php'; ?>
