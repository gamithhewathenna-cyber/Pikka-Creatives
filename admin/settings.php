<?php
$current_page = 'settings'; $page_title = 'Site Settings';
require_once __DIR__ . '/auth.php';
require_login();

$fields = [
    'site_name'           => ['Site name', 'text'],
    'logo_text'           => ['Logo text', 'text'],
    'accent_color'        => ['Accent colour', 'color'],
    'email'               => ['Contact email', 'text'],
    'phone'               => ['Contact phone', 'text'],
    'marquee_items'       => ['Marquee items (separate with | )', 'textarea'],
    'footer_text'         => ['Footer text', 'textarea'],
    'maintenance_mode'    => ['Maintenance mode', 'checkbox'],
    'maintenance_message' => ['Maintenance message', 'textarea'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $_POST['maintenance_mode'] = isset($_POST['maintenance_mode']) ? '1' : '0';
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
<?php require __DIR__ . '/footer.php'; ?>
