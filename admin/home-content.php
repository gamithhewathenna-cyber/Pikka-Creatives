<?php
$current_page = 'home-content'; $page_title = 'Home Page Content';
require_once __DIR__ . '/auth.php';
require_login();

$tabs = [
    'slider'   => ['label' => 'Hero Slider'],
    'sections' => ['label' => 'Text & Sections'],
    'services' => ['label' => 'Services'],
    'why'      => ['label' => 'Why Choose Us'],
    'process'  => ['label' => 'Process Steps'],
    'stats'    => ['label' => 'Stats Bar'],
    'images'   => ['label' => 'Images'],
];
$tab = $_GET['tab'] ?? 'sections';
if (!array_key_exists($tab, $tabs)) $tab = 'sections';

$crud = [
    'services' => ['table' => 'services', 'intro' => 'These appear in the accordion (Section 3). Use the arrows to reorder.',
                    'columns' => ['title' => ['label' => 'Service title', 'type' => 'text'], 'description' => ['label' => 'Description', 'type' => 'textarea']]],
    'why'      => ['table' => 'why_reasons', 'intro' => 'The four reasons grid (Section 4).',
                    'columns' => ['title' => ['label' => 'Reason title', 'type' => 'text'], 'description' => ['label' => 'Description', 'type' => 'textarea']]],
    'process'  => ['table' => 'process_steps', 'intro' => 'Your working process (Section 5).',
                    'columns' => ['step_number' => ['label' => 'Step number (e.g. 01)', 'type' => 'text'], 'title' => ['label' => 'Step title', 'type' => 'text'], 'description' => ['label' => 'Description', 'type' => 'textarea']]],
    'stats'    => ['table' => 'stats', 'intro' => 'The trust bar cards (Section 7).',
                    'columns' => ['title' => ['label' => 'Stat title', 'type' => 'text'], 'description' => ['label' => 'Description', 'type' => 'textarea']]],
];

$image_keys = [
    'hero_image'       => 'Hero image (the person / brand photo in the circle)',
    'intro_image'      => 'Intro section image (optional)',
    'industries_image' => 'Industries section image (optional)',
];

// The hero_slides table is only on sites that have run the latest sql/pikka_db.sql.
// Guard against it so a missing table shows a clear message instead of a fatal error.
$sliderTableReady = (bool) mysqli_query(db(), "SELECT 1 FROM hero_slides LIMIT 1");

/* ---------------- POST handling ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    if ($tab === 'sections') {
        $fields = $_POST['field'] ?? [];
        $stmt = mysqli_prepare(db(), "UPDATE page_content SET content_value = ? WHERE content_key = ?");
        foreach ($fields as $key => $val) {
            $val = (string)$val;
            mysqli_stmt_bind_param($stmt, 'ss', $val, $key);
            mysqli_stmt_execute($stmt);
        }
        flash('Section text updated successfully.');
    }
    elseif ($tab === 'slider' && !$sliderTableReady) {
        flash('The Hero Slider needs a one-time database update before it can be used — see the note on this page for the SQL to run.');
    }
    elseif ($tab === 'slider') {
        $action = $_POST['action'] ?? '';

        if ($action === 'add' || $action === 'edit') {
            $eyebrow    = trim($_POST['eyebrow'] ?? '');
            $headline   = trim($_POST['headline'] ?? '');
            $subhead    = trim($_POST['subheadline'] ?? '');
            $btnPText   = trim($_POST['btn_primary_text'] ?? '');
            $btnPLink   = trim($_POST['btn_primary_link'] ?? '') ?: '#services';
            $btnSText   = trim($_POST['btn_secondary_text'] ?? '');

            $imagePath = null; // null = leave existing image untouched (edit only)
            if (!empty($_FILES['image']['name'])) {
                $uploadDir = __DIR__ . '/../uploads/';
                if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);
                $f = $_FILES['image'];
                $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif'];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                $finfo = @getimagesize($f['tmp_name']);
                if ($f['error'] !== 0) { flash('Upload error.'); }
                elseif (!isset($allowed[$ext]) || !$finfo) { flash('Please upload a JPG, PNG, WEBP or GIF image.'); }
                elseif ($f['size'] > 5 * 1024 * 1024) { flash('Image must be under 5 MB.'); }
                else {
                    $name = 'hero_slide_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                    if (move_uploaded_file($f['tmp_name'], $uploadDir . $name)) {
                        $imagePath = 'uploads/' . $name;
                    } else { flash('Could not save the uploaded image. Check the uploads/ folder permissions (755).'); }
                }
            }

            if ($action === 'add') {
                $ord = mysqli_fetch_assoc(mysqli_query(db(), "SELECT COALESCE(MAX(sort_order),0)+1 n FROM hero_slides"))['n'];
                $img = $imagePath ?? '';
                $stmt = mysqli_prepare(db(), "INSERT INTO hero_slides (eyebrow, headline, subheadline, btn_primary_text, btn_primary_link, btn_secondary_text, image, sort_order) VALUES (?,?,?,?,?,?,?,?)");
                mysqli_stmt_bind_param($stmt, 'sssssssi', $eyebrow, $headline, $subhead, $btnPText, $btnPLink, $btnSText, $img, $ord);
                mysqli_stmt_execute($stmt);
                flash('Slide added.');
            } else {
                $id = (int)($_POST['id'] ?? 0);
                if ($imagePath !== null) {
                    $stmt = mysqli_prepare(db(), "UPDATE hero_slides SET eyebrow=?, headline=?, subheadline=?, btn_primary_text=?, btn_primary_link=?, btn_secondary_text=?, image=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt, 'sssssssi', $eyebrow, $headline, $subhead, $btnPText, $btnPLink, $btnSText, $imagePath, $id);
                } else {
                    $stmt = mysqli_prepare(db(), "UPDATE hero_slides SET eyebrow=?, headline=?, subheadline=?, btn_primary_text=?, btn_primary_link=?, btn_secondary_text=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt, 'ssssssi', $eyebrow, $headline, $subhead, $btnPText, $btnPLink, $btnSText, $id);
                }
                mysqli_stmt_execute($stmt);
                flash('Slide updated.');
            }
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = mysqli_prepare(db(), "DELETE FROM hero_slides WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            flash('Slide deleted.');
        }
        elseif ($action === 'move') {
            $id = (int)($_POST['id'] ?? 0);
            $dir = $_POST['dir'] === 'up' ? 'up' : 'down';
            $rows = [];
            $r = mysqli_query(db(), "SELECT id, sort_order FROM hero_slides ORDER BY sort_order ASC, id ASC");
            while ($x = mysqli_fetch_assoc($r)) $rows[] = $x;
            for ($i = 0; $i < count($rows); $i++) {
                if ((int)$rows[$i]['id'] === $id) {
                    $j = $dir === 'up' ? $i - 1 : $i + 1;
                    if ($j >= 0 && $j < count($rows)) {
                        $a = $rows[$i]['id']; $b = $rows[$j]['id'];
                        $oa = $rows[$i]['sort_order']; $ob = $rows[$j]['sort_order'];
                        mysqli_query(db(), "UPDATE hero_slides SET sort_order=$ob WHERE id=$a");
                        mysqli_query(db(), "UPDATE hero_slides SET sort_order=$oa WHERE id=$b");
                    }
                    break;
                }
            }
        }
    }
    elseif (isset($crud[$tab])) {
        $table   = $crud[$tab]['table'];
        $columns = $crud[$tab]['columns'];
        $action  = $_POST['action'] ?? '';

        if ($action === 'add' || $action === 'edit') {
            $cols = array_keys($columns);
            $vals = [];
            foreach ($cols as $col) $vals[$col] = trim($_POST[$col] ?? '');

            if ($action === 'add') {
                $ord = mysqli_fetch_assoc(mysqli_query(db(), "SELECT COALESCE(MAX(sort_order),0)+1 n FROM `$table`"))['n'];
                $colList = implode(',', array_map(fn($c) => "`$c`", $cols)) . ',sort_order';
                $ph = implode(',', array_fill(0, count($cols) + 1, '?'));
                $types = str_repeat('s', count($cols)) . 'i';
                $stmt = mysqli_prepare(db(), "INSERT INTO `$table` ($colList) VALUES ($ph)");
                $bind = array_values($vals); $bind[] = $ord;
                mysqli_stmt_bind_param($stmt, $types, ...$bind);
                mysqli_stmt_execute($stmt);
                flash('Item added.');
            } else {
                $id = (int)($_POST['id'] ?? 0);
                $set = implode(',', array_map(fn($c) => "`$c`=?", $cols));
                $types = str_repeat('s', count($cols)) . 'i';
                $stmt = mysqli_prepare(db(), "UPDATE `$table` SET $set WHERE id=?");
                $bind = array_values($vals); $bind[] = $id;
                mysqli_stmt_bind_param($stmt, $types, ...$bind);
                mysqli_stmt_execute($stmt);
                flash('Item updated.');
            }
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = mysqli_prepare(db(), "DELETE FROM `$table` WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            flash('Item deleted.');
        }
        elseif ($action === 'move') {
            $id = (int)($_POST['id'] ?? 0);
            $dir = $_POST['dir'] === 'up' ? 'up' : 'down';
            $rows = [];
            $r = mysqli_query(db(), "SELECT id, sort_order FROM `$table` ORDER BY sort_order ASC, id ASC");
            while ($x = mysqli_fetch_assoc($r)) $rows[] = $x;
            for ($i = 0; $i < count($rows); $i++) {
                if ((int)$rows[$i]['id'] === $id) {
                    $j = $dir === 'up' ? $i - 1 : $i + 1;
                    if ($j >= 0 && $j < count($rows)) {
                        $a = $rows[$i]['id']; $b = $rows[$j]['id'];
                        $oa = $rows[$i]['sort_order']; $ob = $rows[$j]['sort_order'];
                        mysqli_query(db(), "UPDATE `$table` SET sort_order=$ob WHERE id=$a");
                        mysqli_query(db(), "UPDATE `$table` SET sort_order=$oa WHERE id=$b");
                    }
                    break;
                }
            }
        }
    }
    elseif ($tab === 'images') {
        $key = $_POST['key'] ?? '';
        if (array_key_exists($key, $image_keys)) {
            if (($_POST['do'] ?? '') === 'remove') {
                $st = mysqli_prepare(db(), "UPDATE page_content SET content_value='' WHERE content_key=?");
                mysqli_stmt_bind_param($st, 's', $key); mysqli_stmt_execute($st);
                flash('Image removed.');
            } elseif (!empty($_FILES['image']['name'])) {
                $uploadDir = __DIR__ . '/../uploads/';
                if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);
                $f = $_FILES['image'];
                $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif'];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                $finfo = @getimagesize($f['tmp_name']);
                if ($f['error'] !== 0) { flash('Upload error.'); }
                elseif (!isset($allowed[$ext]) || !$finfo) { flash('Please upload a JPG, PNG, WEBP or GIF image.'); }
                elseif ($f['size'] > 5 * 1024 * 1024) { flash('Image must be under 5 MB.'); }
                else {
                    $name = $key . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($f['tmp_name'], $uploadDir . $name)) {
                        $path = 'uploads/' . $name;
                        $st = mysqli_prepare(db(), "UPDATE page_content SET content_value=? WHERE content_key=?");
                        mysqli_stmt_bind_param($st, 'ss', $path, $key); mysqli_stmt_execute($st);
                        flash('Image uploaded.');
                    } else { flash('Could not save the uploaded file. Check the uploads/ folder permissions (755).'); }
                }
            }
        } else {
            flash('Unknown image slot.');
        }
    }

    header('Location: home-content.php?tab=' . $tab); exit;
}

/* ---------------- Data for the active tab ---------------- */
if ($tab === 'slider') {
    $rows = get_rows('hero_slides');
}
elseif ($tab === 'sections') {
    $res = mysqli_query(db(), "SELECT * FROM page_content WHERE content_key NOT LIKE '%_image' ORDER BY id ASC");
    $groups = [];
    while ($row = mysqli_fetch_assoc($res)) $groups[$row['section']][] = $row;
}
elseif (isset($crud[$tab])) {
    $table = $crud[$tab]['table'];
    $rows = [];
    $r = mysqli_query(db(), "SELECT * FROM `$table` ORDER BY sort_order ASC, id ASC");
    while ($x = mysqli_fetch_assoc($r)) $rows[] = $x;
}
elseif ($tab === 'images') {
    foreach ($image_keys as $k => $label) {
        $st = mysqli_prepare(db(), "INSERT IGNORE INTO page_content (content_key, content_value, section, label, field_type) VALUES (?, '', 'Images', ?, 'image')");
        mysqli_stmt_bind_param($st, 'ss', $k, $label);
        mysqli_stmt_execute($st);
    }
}

function render_fields($columns, $data = []) {
    foreach ($columns as $col => $meta) {
        $label = $meta['label']; $type = $meta['type'] ?? 'text';
        $val = $data[$col] ?? '';
        echo '<div class="field"><label>' . e($label) . '</label>';
        if ($type === 'textarea') {
            echo '<textarea name="' . e($col) . '" rows="3">' . e($val) . '</textarea>';
        } else {
            echo '<input type="text" name="' . e($col) . '" value="' . e($val) . '">';
        }
        echo '</div>';
    }
}

require __DIR__ . '/header.php';
?>
<div class="subnav">
  <?php foreach ($tabs as $key => $meta): ?>
    <a href="?tab=<?= e($key) ?>" class="<?= $tab === $key ? 'active' : '' ?>"><?= e($meta['label']) ?></a>
  <?php endforeach; ?>
</div>

<?php if ($tab === 'slider' && !$sliderTableReady): ?>
  <div class="card">
    <h2>One-time setup needed</h2>
    <p class="sub">The Hero Slider stores its slides in a database table that doesn't exist on this site yet. Open <strong>phpMyAdmin</strong> (in cPanel), select your database, go to the <strong>SQL</strong> tab, paste the code below, and click Go. This only needs to be done once — nothing else on the site is affected.</p>
    <textarea readonly rows="14" style="font-family:monospace;font-size:12.5px;white-space:pre" onclick="this.select()">CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `eyebrow` VARCHAR(160) DEFAULT NULL,
  `headline` VARCHAR(255) DEFAULT NULL,
  `subheadline` TEXT DEFAULT NULL,
  `btn_primary_text` VARCHAR(80) DEFAULT NULL,
  `btn_primary_link` VARCHAR(255) DEFAULT NULL,
  `btn_secondary_text` VARCHAR(80) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `hero_slides` (`eyebrow`, `headline`, `subheadline`, `btn_primary_text`, `btn_primary_link`, `btn_secondary_text`, `image`, `sort_order`) VALUES
('Creative and Digital Solutions · New Zealand', 'Your vision, brought to life.', 'Pikka Creatives helps businesses from Kaitaia to Bluff grow through smart design, high-performing websites and digital marketing, backed by local support.', 'See our work', '#services', 'Start a project', '', 1);</textarea>
    <p class="muted" style="margin-top:10px">Once that's run, refresh this page and the slider editor will appear here.</p>
  </div>
<?php elseif ($tab === 'slider'): ?>
  <div class="card">
    <h2>Hero Slider</h2>
    <p class="sub">The rotating banner at the top of the home page. Each slide has its own headline, sub-headline, buttons and image, and the slider crossfades between them automatically. With only one slide, it's shown as a static hero (no rotation).</p>
  </div>
  <?php foreach ($rows as $idx => $row): ?>
  <div class="card">
    <div class="rh" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
      <span class="pill">Slide <?= $idx + 1 ?></span>
      <div style="display:flex;gap:6px">
        <form method="post" action="?tab=slider" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="dir" value="up"><button class="btn btn-ghost btn-sm" <?= $idx === 0 ? 'disabled' : '' ?>>↑</button></form>
        <form method="post" action="?tab=slider" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="dir" value="down"><button class="btn btn-ghost btn-sm" <?= $idx === count($rows) - 1 ? 'disabled' : '' ?>>↓</button></form>
        <form method="post" action="?tab=slider" style="display:inline" onsubmit="return confirm('Delete this slide?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $row['id'] ?>"><button class="btn btn-danger btn-sm">Delete</button></form>
      </div>
    </div>
    <form method="post" action="?tab=slider" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <div class="img-field" style="margin-bottom:18px">
        <?php if ($row['image']): ?>
          <img class="thumb" src="../<?= e($row['image']) ?>" alt="">
        <?php else: ?>
          <div class="thumb" style="display:grid;place-items:center;color:#aaa;font-size:11px">none</div>
        <?php endif; ?>
        <div style="flex:1">
          <label>Slide image</label>
          <input type="file" name="image" accept="image/*">
          <div class="muted" style="margin-top:6px">Leave empty to keep the current image.</div>
        </div>
      </div>
      <div class="two">
        <div class="field"><label>Eyebrow</label><input type="text" name="eyebrow" value="<?= e($row['eyebrow']) ?>"></div>
        <div class="field"><label>Headline</label><input type="text" name="headline" value="<?= e($row['headline']) ?>"></div>
      </div>
      <div class="field"><label>Sub-headline</label><textarea name="subheadline" rows="2"><?= e($row['subheadline']) ?></textarea></div>
      <div class="two">
        <div class="field"><label>Primary button text</label><input type="text" name="btn_primary_text" value="<?= e($row['btn_primary_text']) ?>"></div>
        <div class="field"><label>Primary button link</label><input type="text" name="btn_primary_link" value="<?= e($row['btn_primary_link']) ?>" placeholder="#services"></div>
      </div>
      <div class="field"><label>Secondary button text (always opens the "Start a project" pop-up)</label><input type="text" name="btn_secondary_text" value="<?= e($row['btn_secondary_text']) ?>"></div>
      <button class="btn btn-sm" type="submit">Save changes</button>
    </form>
  </div>
  <?php endforeach; ?>
  <div class="card" style="border-style:dashed">
    <h2>Add new slide</h2>
    <form method="post" action="?tab=slider" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="add">
      <div class="field"><label>Slide image</label><input type="file" name="image" accept="image/*"></div>
      <div class="two">
        <div class="field"><label>Eyebrow</label><input type="text" name="eyebrow" placeholder="Creative and Digital Solutions · New Zealand"></div>
        <div class="field"><label>Headline</label><input type="text" name="headline" placeholder="Your vision, brought to life."></div>
      </div>
      <div class="field"><label>Sub-headline</label><textarea name="subheadline" rows="2"></textarea></div>
      <div class="two">
        <div class="field"><label>Primary button text</label><input type="text" name="btn_primary_text" placeholder="See our work"></div>
        <div class="field"><label>Primary button link</label><input type="text" name="btn_primary_link" placeholder="#services"></div>
      </div>
      <div class="field"><label>Secondary button text</label><input type="text" name="btn_secondary_text" placeholder="Start a project"></div>
      <button class="btn" type="submit">+ Add slide</button>
    </form>
  </div>

<?php elseif ($tab === 'sections'): ?>
  <form method="post" action="?tab=sections">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <?php foreach ($groups as $section => $srows): ?>
      <div class="card">
        <h2><?= e($section) ?> section</h2>
        <p class="sub">Edit the copy for this part of the home page.</p>
        <?php foreach ($srows as $r):
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

<?php elseif (isset($crud[$tab])):
  $conf = $crud[$tab]; ?>
  <div class="card">
    <h2><?= e($tabs[$tab]['label']) ?></h2>
    <p class="sub"><?= e($conf['intro']) ?></p>
  </div>
  <?php foreach ($rows as $idx => $row): ?>
  <div class="card">
    <div class="rh" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
      <span class="pill">#<?= $idx + 1 ?></span>
      <div style="display:flex;gap:6px">
        <form method="post" action="?tab=<?= e($tab) ?>" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="dir" value="up"><button class="btn btn-ghost btn-sm" <?= $idx === 0 ? 'disabled' : '' ?>>↑</button></form>
        <form method="post" action="?tab=<?= e($tab) ?>" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="dir" value="down"><button class="btn btn-ghost btn-sm" <?= $idx === count($rows) - 1 ? 'disabled' : '' ?>>↓</button></form>
        <form method="post" action="?tab=<?= e($tab) ?>" style="display:inline" onsubmit="return confirm('Delete this item?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $row['id'] ?>"><button class="btn btn-danger btn-sm">Delete</button></form>
      </div>
    </div>
    <form method="post" action="?tab=<?= e($tab) ?>">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <?php render_fields($conf['columns'], $row); ?>
      <button class="btn btn-sm" type="submit">Save changes</button>
    </form>
  </div>
  <?php endforeach; ?>
  <div class="card" style="border-style:dashed">
    <h2>Add new</h2>
    <form method="post" action="?tab=<?= e($tab) ?>">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="add">
      <?php render_fields($conf['columns']); ?>
      <button class="btn" type="submit">+ Add item</button>
    </form>
  </div>

<?php elseif ($tab === 'images'): ?>
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
        <form method="post" action="?tab=images" enctype="multipart/form-data" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="key" value="<?= e($key) ?>">
          <input type="file" name="image" accept="image/*" required style="max-width:280px">
          <button class="btn btn-sm" type="submit">Upload</button>
        </form>
        <?php if ($current): ?>
        <form method="post" action="?tab=images" style="margin-top:8px" onsubmit="return confirm('Remove this image?')">
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
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
