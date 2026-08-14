<?php
$current_page = 'settings'; $page_title = 'Site Settings';
require_once __DIR__ . '/auth.php';
require_login();
require_admin_role();

$fields = [
    'site_name'           => ['Site name', 'text'],
    'logo_text'           => ['Logo text', 'text'],
    'accent_color'        => ['Accent colour', 'color'],
    'email'               => ['Contact email', 'text'],
    'phone'               => ['Contact phone', 'text'],
    'marquee_items'       => ['Marquee items (separate with | )', 'textarea'],
    'footer_text'         => ['Footer text', 'textarea'],
    'ga_id'               => ['Google Analytics Measurement ID', 'text'],
    'maintenance_mode'    => ['Maintenance mode', 'checkbox'],
    'maintenance_message' => ['Maintenance message', 'textarea'],
];

$logo_slots = [
    'logo_image'       => 'Colour logo (used in the header)',
    'logo_image_white' => 'White logo (used in the dark footer)',
    'favicon'          => 'Favicon (shown in the browser tab)',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $formType = $_POST['form_type'] ?? 'text';

    if ($formType === 'logo') {
        $key = $_POST['logo_key'] ?? '';
        if (!array_key_exists($key, $logo_slots)) {
            flash('Unknown logo slot.');
        } elseif (($_POST['do'] ?? '') === 'remove') {
            $st = mysqli_prepare(db(), "UPDATE site_settings SET setting_value='' WHERE setting_key=?");
            mysqli_stmt_bind_param($st, 's', $key); mysqli_stmt_execute($st);
            flash('Logo removed.');
        } elseif (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);
            $f = $_FILES['image'];
            $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'ico' => 'image/x-icon'];
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            // getimagesize() can't reliably read SVG or ICO, so skip that check for those two.
            $skipImageCheck = $ext === 'svg' || $ext === 'ico';
            $finfo = $skipImageCheck ? true : @getimagesize($f['tmp_name']);
            if ($f['error'] !== 0) { flash('Upload error.'); }
            elseif (!isset($allowed[$ext]) || !$finfo) { flash('Please upload a JPG, PNG, WEBP, GIF, SVG or ICO image.'); }
            elseif ($f['size'] > 5 * 1024 * 1024) { flash('Image must be under 5 MB.'); }
            else {
                $name = $key . '_' . time() . '.' . $ext;
                if (move_uploaded_file($f['tmp_name'], $uploadDir . $name)) {
                    $path = 'uploads/' . $name;
                    $st = mysqli_prepare(db(), "INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)
                                                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
                    mysqli_stmt_bind_param($st, 'ss', $key, $path); mysqli_stmt_execute($st);
                    flash('Logo uploaded.');
                } else { flash('Could not save the uploaded file. Check the uploads/ folder permissions (755).'); }
            }
        }
        header('Location: settings.php'); exit;
    }

    $_POST['maintenance_mode'] = isset($_POST['maintenance_mode']) ? '1' : '0';
    if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', trim($_POST['accent_color'] ?? ''))) {
        $_POST['accent_color'] = s('accent_color', '#F1592A');
    }
    $st = mysqli_prepare(db(), "INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)
                                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    foreach ($fields as $key => $meta) {
        $val = trim($_POST[$key] ?? '');
        mysqli_stmt_bind_param($st, 'ss', $key, $val);
        mysqli_stmt_execute($st);
    }
    flash('Settings saved.');
    header('Location: settings.php'); exit;
}

require __DIR__ . '/header.php';
?>
<div class="card">
  <h2>Logo &amp; favicon</h2>
  <p class="sub">Upload real logo images if you have them. The header sits on a light background (use your colour logo) and the footer sits on a dark background (use a white / light version). If a slot is left empty, the site falls back to the text logo below (or, for the favicon, a default mark).</p>
  <?php foreach ($logo_slots as $key => $label):
      $current = s($key);
      $isFavicon = $key === 'favicon';
      $preview = $key === 'logo_image_white' ? 'background:var(--ink)' : 'background:#fff'; ?>
  <div class="img-field" style="margin-bottom:<?= $key === array_key_last($logo_slots) ? '0' : '18px' ?>">
    <?php if ($current): ?>
      <img class="thumb" style="<?= $preview ?>;object-fit:contain;padding:6px" src="../<?= e($current) ?>" alt="">
    <?php else: ?>
      <div class="thumb" style="display:grid;place-items:center;color:#aaa;font-size:11px">none</div>
    <?php endif; ?>
    <div style="flex:1">
      <label style="font-family:Sora;font-size:15px"><?= e($label) ?></label>
      <div class="muted" style="margin-bottom:10px"><?= $current ? e($current) : ($isFavicon ? 'No favicon set — using the default mark' : 'No logo set — using the text logo') ?></div>
      <form method="post" enctype="multipart/form-data" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="form_type" value="logo">
        <input type="hidden" name="logo_key" value="<?= e($key) ?>">
        <input type="file" name="image" accept="<?= $isFavicon ? 'image/*,.ico' : 'image/*' ?>" required style="max-width:280px">
        <button class="btn btn-sm" type="submit">Upload</button>
      </form>
      <?php if ($isFavicon): ?><div class="muted" style="margin-top:6px">A square image works best — PNG, SVG or ICO.</div><?php endif; ?>
      <?php if ($current): ?>
      <form method="post" style="margin-top:8px" onsubmit="return confirm('Remove this logo?')">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="form_type" value="logo">
        <input type="hidden" name="logo_key" value="<?= e($key) ?>">
        <input type="hidden" name="do" value="remove">
        <button class="btn btn-ghost btn-sm">Remove</button>
      </form>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<form method="post">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_type" value="text">
  <div class="card">
    <h2>Branding & contact</h2>
    <p class="sub">Logo text, accent colour and contact details used across the site.</p>
    <div class="two">
      <div class="field"><label>Site name</label><input type="text" name="site_name" value="<?= e(s('site_name')) ?>"></div>
      <div class="field"><label>Logo text (used if no logo image is uploaded above)</label><input type="text" name="logo_text" value="<?= e(s('logo_text')) ?>"></div>
    </div>
    <div class="two">
      <div class="field">
        <label>Accent colour</label>
        <div class="colour-field">
          <span class="swatch" id="accentSwatch" style="background:<?= e(s('accent_color','#F1592A')) ?>"></span>
          <input type="text" name="accent_color" id="accentInput" value="<?= e(s('accent_color','#F1592A')) ?>" placeholder="#00c4a1" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" maxlength="7" autocomplete="off">
        </div>
        <p class="muted" style="margin-top:6px">Hex code, e.g. #00c4a1</p>
      </div>
      <div class="field"><label>Contact email</label><input type="text" name="email" value="<?= e(s('email')) ?>"></div>
    </div>
    <div class="field"><label>Contact phone</label><input type="text" name="phone" value="<?= e(s('phone')) ?>"></div>
  </div>
  <div class="card">
    <h2>Marquee & footer</h2>
    <p class="sub">The scrolling strip under the hero, and the footer description. Social links and other contact details are managed under <a href="page-content.php?tab=contact">Contact Us</a>.</p>
    <div class="field"><label>Marquee items (separate with | )</label><textarea name="marquee_items" rows="2"><?= e(s('marquee_items')) ?></textarea></div>
    <div class="field"><label>Footer description (shown under the logo, in every page's footer)</label><textarea name="footer_text" rows="2"><?= e(s('footer_text')) ?></textarea></div>
  </div>
  <div class="card">
    <h2>Analytics</h2>
    <p class="sub">Connects the site to Google Analytics (GA4). Find your Measurement ID in Google Analytics under Admin → Data Streams → your web stream — it looks like <code>G-XXXXXXXXXX</code>. Leave blank to disable tracking.</p>
    <div class="field"><label>Google Analytics Measurement ID</label><input type="text" name="ga_id" value="<?= e(s('ga_id')) ?>" placeholder="G-XXXXXXXXXX"></div>
  </div>
  <div class="card">
    <h2>Maintenance mode</h2>
    <p class="sub">Take the site offline for visitors while you work on it. Logged-in admins still see the live site as normal.</p>
    <div class="field">
      <label><input type="checkbox" name="maintenance_mode" value="1" <?= s('maintenance_mode') === '1' ? 'checked' : '' ?>> Enable maintenance mode</label>
    </div>
    <div class="field"><label>Maintenance message</label><textarea name="maintenance_message" rows="3" placeholder="We're currently making some improvements. Please check back soon."><?= e(s('maintenance_message')) ?></textarea></div>
  </div>
  <button class="btn" type="submit">Save settings</button>
</form>
<script>
(function () {
  var input = document.getElementById('accentInput');
  var swatch = document.getElementById('accentSwatch');
  if (!input || !swatch) return;
  input.addEventListener('input', function () {
    if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(input.value.trim())) {
      swatch.style.background = input.value.trim();
    }
  });
})();
</script>
<?php require __DIR__ . '/footer.php'; ?>
