<?php
$current_page = 'images'; $page_title = 'Images';
require_once __DIR__ . '/auth.php';
require_login();

// Image content keys we manage (stored in page_content so front-end reads via c())
$image_keys = [
    'hero_image'       => 'Hero image (the person / brand photo in the circle)',
    'intro_image'      => 'Intro section image (optional)',
    'industries_image' => 'Industries section image (optional)',
];
// ensure rows exist
foreach ($image_keys as $k => $label) {
    $st = mysqli_prepare(db(), "INSERT IGNORE INTO page_content (content_key, content_value, section, label, field_type) VALUES (?, '', 'Images', ?, 'image')");
    mysqli_stmt_bind_param($st, 'ss', $k, $label);
    mysqli_stmt_execute($st);
}

$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $key = $_POST['key'] ?? '';
    if (!array_key_exists($key, $image_keys)) { flash('Unknown image slot.'); header('Location: images.php'); exit; }

    if (($_POST['do'] ?? '') === 'remove') {
        $st = mysqli_prepare(db(), "UPDATE page_content SET content_value='' WHERE content_key=?");
        mysqli_stmt_bind_param($st, 's', $key); mysqli_stmt_execute($st);
        flash('Image removed.'); header('Location: images.php'); exit;
    }

    if (!empty($_FILES['image']['name'])) {
        $f = $_FILES['image'];
        $allowed = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','webp'=>'image/webp','gif'=>'image/gif'];
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        $finfo = @getimagesize($f['tmp_name']);
        if ($f['error'] !== 0) { flash('Upload error.'); }
        elseif (!isset($allowed[$ext]) || !$finfo) { flash('Please upload a JPG, PNG, WEBP or GIF image.'); }
        elseif ($f['size'] > 5*1024*1024) { flash('Image must be under 5 MB.'); }
        else {
            $name = $key . '_' . time() . '.' . $ext;
            if (move_uploaded_file($f['tmp_name'], $uploadDir . $name)) {
                $path = 'uploads/' . $name; // relative to site root, used by index.php
                $st = mysqli_prepare(db(), "UPDATE page_content SET content_value=? WHERE content_key=?");
                mysqli_stmt_bind_param($st, 'ss', $path, $key); mysqli_stmt_execute($st);
                flash('Image uploaded.');
            } else { flash('Could not save the uploaded file. Check the uploads/ folder permissions (755).'); }
        }
    }
    header('Location: images.php'); exit;
}

require __DIR__ . '/header.php';
?>
<div class="card">
  <h2>Home page images</h2>
  <p class="sub">Upload photos for the home page. Square-ish images work best for the hero circle. Max 5 MB.</p>
</div>

<?php foreach ($image_keys as $key => $label):
    $current = c($key); ?>
<div class="card">
  <div class="img-field">
    <?php if ($current): ?>
      <img class="thumb" src="../<?= e($current) ?>" alt="">
    <?php else: ?>
      <div class="thumb" style="display:grid;place-items:center;color:#aaa;font-size:11px">none</div>
    <?php endif; ?>
    <div style="flex:1">
      <label style="font-family:Sora;font-size:15px"><?= e($label) ?></label>
      <div class="muted" style="margin-bottom:10px"><?= $current ? e($current) : 'No image set' ?></div>
      <form method="post" enctype="multipart/form-data" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="key" value="<?= e($key) ?>">
        <input type="file" name="image" accept="image/*" required style="max-width:280px">
        <button class="btn btn-sm" type="submit">Upload</button>
      </form>
      <?php if ($current): ?>
      <form method="post" style="margin-top:8px" onsubmit="return confirm('Remove this image?')">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="key" value="<?= e($key) ?>">
        <input type="hidden" name="do" value="remove">
        <button class="btn btn-ghost btn-sm">Remove</button>
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endforeach; ?>
<?php require __DIR__ . '/footer.php'; ?>
