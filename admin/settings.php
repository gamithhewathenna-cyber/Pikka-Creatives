<?php
$current_page = 'settings'; $page_title = 'Site Settings';
require_once __DIR__ . '/auth.php';
require_login();

$fields = [
    'site_name'     => ['Site name', 'text'],
    'logo_text'     => ['Logo text', 'text'],
    'accent_color'  => ['Accent colour', 'color'],
    'email'         => ['Contact email', 'text'],
    'phone'         => ['Contact phone', 'text'],
    'marquee_items' => ['Marquee items (separate with | )', 'textarea'],
    'footer_text'   => ['Footer text', 'textarea'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $st = mysqli_prepare(db(), "UPDATE site_settings SET setting_value=? WHERE setting_key=?");
    foreach ($fields as $key => $meta) {
        $val = trim($_POST[$key] ?? '');
        mysqli_stmt_bind_param($st, 'ss', $val, $key);
        mysqli_stmt_execute($st);
    }
    flash('Settings saved.');
    header('Location: settings.php'); exit;
}

require __DIR__ . '/header.php';
?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <div class="card">
    <h2>Branding & contact</h2>
    <p class="sub">Logo text, accent colour and contact details used across the site.</p>
    <div class="two">
      <div class="field"><label>Site name</label><input type="text" name="site_name" value="<?= e(s('site_name')) ?>"></div>
      <div class="field"><label>Logo text</label><input type="text" name="logo_text" value="<?= e(s('logo_text')) ?>"></div>
    </div>
    <div class="two">
      <div class="field"><label>Accent colour</label><input type="color" name="accent_color" value="<?= e(s('accent_color','#F1592A')) ?>"></div>
      <div class="field"><label>Contact email</label><input type="text" name="email" value="<?= e(s('email')) ?>"></div>
    </div>
    <div class="field"><label>Contact phone</label><input type="text" name="phone" value="<?= e(s('phone')) ?>"></div>
  </div>
  <div class="card">
    <h2>Marquee & footer</h2>
    <p class="sub">The scrolling strip under the hero, and the footer line.</p>
    <div class="field"><label>Marquee items (separate with | )</label><textarea name="marquee_items" rows="2"><?= e(s('marquee_items')) ?></textarea></div>
    <div class="field"><label>Footer text</label><textarea name="footer_text" rows="2"><?= e(s('footer_text')) ?></textarea></div>
  </div>
  <button class="btn" type="submit">Save settings</button>
</form>
<?php require __DIR__ . '/footer.php'; ?>
