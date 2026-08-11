<?php
require_once __DIR__ . '/auth.php';
require_login();

$tabs = [
    'projects'   => ['label' => 'Projects'],
    'categories' => ['label' => 'Categories'],
];
$tab = $_GET['tab'] ?? 'projects';
if (!array_key_exists($tab, $tabs)) $tab = 'projects';

$current_page = 'work';
$page_title = 'Our Work';

// work_categories / work_projects are only on sites that have run the latest
// sql/pikka_db.sql. Guard against them so a missing table shows a clear
// message instead of a fatal error.
$workTablesReady = (bool) mysqli_query(db(), "SELECT 1 FROM work_categories LIMIT 1")
    && (bool) mysqli_query(db(), "SELECT 1 FROM work_projects LIMIT 1");

/* ---------------- POST handling ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    if (!$workTablesReady) {
        flash('Our Work needs a one-time database update before it can be used — see the note on this page for the SQL to run.');
        header('Location: work.php?tab=' . $tab); exit;
    }

    $action = $_POST['action'] ?? '';

    if ($tab === 'categories') {
        if ($action === 'add' || $action === 'edit') {
            $name = trim($_POST['name'] ?? '');
            $id   = (int)($_POST['id'] ?? 0);

            $baseSlug = slugify($name);
            $slug = $baseSlug;
            $i = 2;
            while (true) {
                $st = mysqli_prepare(db(), "SELECT id FROM work_categories WHERE slug=? AND id<>?");
                mysqli_stmt_bind_param($st, 'si', $slug, $id);
                mysqli_stmt_execute($st);
                $exists = mysqli_fetch_assoc(mysqli_stmt_get_result($st));
                if (!$exists) break;
                $slug = $baseSlug . '-' . $i;
                $i++;
            }

            if ($action === 'add') {
                $ord = mysqli_fetch_assoc(mysqli_query(db(), "SELECT COALESCE(MAX(sort_order),0)+1 n FROM work_categories"))['n'];
                $stmt = mysqli_prepare(db(), "INSERT INTO work_categories (name, slug, sort_order) VALUES (?,?,?)");
                mysqli_stmt_bind_param($stmt, 'ssi', $name, $slug, $ord);
                mysqli_stmt_execute($stmt);
                flash('Category added.');
            } else {
                $stmt = mysqli_prepare(db(), "UPDATE work_categories SET name=?, slug=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, 'ssi', $name, $slug, $id);
                mysqli_stmt_execute($stmt);
                flash('Category updated.');
            }
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $st = mysqli_prepare(db(), "UPDATE work_projects SET category_id=NULL WHERE category_id=?");
            mysqli_stmt_bind_param($st, 'i', $id); mysqli_stmt_execute($st);
            $stmt = mysqli_prepare(db(), "DELETE FROM work_categories WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt);
            flash('Category deleted. Any projects in it are now uncategorised.');
        }
        elseif ($action === 'move') {
            $id = (int)($_POST['id'] ?? 0);
            $dir = $_POST['dir'] === 'up' ? 'up' : 'down';
            $rows = [];
            $r = mysqli_query(db(), "SELECT id, sort_order FROM work_categories ORDER BY sort_order ASC, id ASC");
            while ($x = mysqli_fetch_assoc($r)) $rows[] = $x;
            for ($i = 0; $i < count($rows); $i++) {
                if ((int)$rows[$i]['id'] === $id) {
                    $j = $dir === 'up' ? $i - 1 : $i + 1;
                    if ($j >= 0 && $j < count($rows)) {
                        $a = $rows[$i]['id']; $b = $rows[$j]['id'];
                        $oa = $rows[$i]['sort_order']; $ob = $rows[$j]['sort_order'];
                        mysqli_query(db(), "UPDATE work_categories SET sort_order=$ob WHERE id=$a");
                        mysqli_query(db(), "UPDATE work_categories SET sort_order=$oa WHERE id=$b");
                    }
                    break;
                }
            }
        }
        header('Location: work.php?tab=categories'); exit;
    }

    elseif ($tab === 'projects') {
        if ($action === 'add' || $action === 'edit') {
            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $link        = trim($_POST['link'] ?? '');
            $categoryId  = (int)($_POST['category_id'] ?? 0);
            $categoryId  = $categoryId > 0 ? $categoryId : null;

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
                    $name = 'work_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                    if (move_uploaded_file($f['tmp_name'], $uploadDir . $name)) {
                        $imagePath = 'uploads/' . $name;
                    } else { flash('Could not save the uploaded image. Check the uploads/ folder permissions (755).'); }
                }
            }

            if ($action === 'add') {
                $ord = mysqli_fetch_assoc(mysqli_query(db(), "SELECT COALESCE(MAX(sort_order),0)+1 n FROM work_projects"))['n'];
                $img = $imagePath ?? '';
                $stmt = mysqli_prepare(db(), "INSERT INTO work_projects (category_id, title, description, image, link, sort_order) VALUES (?,?,?,?,?,?)");
                mysqli_stmt_bind_param($stmt, 'issssi', $categoryId, $title, $description, $img, $link, $ord);
                mysqli_stmt_execute($stmt);
                flash('Project added.');
            } else {
                $id = (int)($_POST['id'] ?? 0);
                if ($imagePath !== null) {
                    $stmt = mysqli_prepare(db(), "UPDATE work_projects SET category_id=?, title=?, description=?, image=?, link=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt, 'issssi', $categoryId, $title, $description, $imagePath, $link, $id);
                } else {
                    $stmt = mysqli_prepare(db(), "UPDATE work_projects SET category_id=?, title=?, description=?, link=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt, 'isssi', $categoryId, $title, $description, $link, $id);
                }
                mysqli_stmt_execute($stmt);
                flash('Project updated.');
            }
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = mysqli_prepare(db(), "DELETE FROM work_projects WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            flash('Project deleted.');
        }
        elseif ($action === 'move') {
            $id = (int)($_POST['id'] ?? 0);
            $dir = $_POST['dir'] === 'up' ? 'up' : 'down';
            $rows = [];
            $r = mysqli_query(db(), "SELECT id, sort_order FROM work_projects ORDER BY sort_order ASC, id ASC");
            while ($x = mysqli_fetch_assoc($r)) $rows[] = $x;
            for ($i = 0; $i < count($rows); $i++) {
                if ((int)$rows[$i]['id'] === $id) {
                    $j = $dir === 'up' ? $i - 1 : $i + 1;
                    if ($j >= 0 && $j < count($rows)) {
                        $a = $rows[$i]['id']; $b = $rows[$j]['id'];
                        $oa = $rows[$i]['sort_order']; $ob = $rows[$j]['sort_order'];
                        mysqli_query(db(), "UPDATE work_projects SET sort_order=$ob WHERE id=$a");
                        mysqli_query(db(), "UPDATE work_projects SET sort_order=$oa WHERE id=$b");
                    }
                    break;
                }
            }
        }
        $redirectQs = http_build_query(array_filter(['tab' => 'projects', 'q' => $_GET['q'] ?? '', 'cat' => $_GET['cat'] ?? '']));
        header('Location: work.php?' . $redirectQs); exit;
    }
}

/* ---------------- Data for the active tab ---------------- */
$categories = get_work_categories();

if ($tab === 'projects') {
    $q         = trim($_GET['q'] ?? '');
    $catFilter = trim($_GET['cat'] ?? '');

    $sql = "SELECT p.*, c.name AS category_name FROM work_projects p LEFT JOIN work_categories c ON c.id = p.category_id WHERE 1=1";
    $params = []; $types = '';
    if ($q !== '') { $sql .= " AND p.title LIKE ?"; $params[] = '%' . $q . '%'; $types .= 's'; }
    if ($catFilter !== '') { $sql .= " AND p.category_id = ?"; $params[] = (int) $catFilter; $types .= 'i'; }
    $sql .= " ORDER BY p.sort_order ASC, p.id ASC";

    $projects = [];
    if ($params) {
        $stmt = mysqli_prepare(db(), $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
    } else {
        $res = mysqli_query(db(), $sql);
    }
    if ($res) while ($row = mysqli_fetch_assoc($res)) $projects[] = $row;
}

require __DIR__ . '/header.php';
?>
<div class="subnav">
  <?php foreach ($tabs as $key => $meta): ?>
    <a href="?tab=<?= e($key) ?>" class="<?= $tab === $key ? 'active' : '' ?>"><?= e($meta['label']) ?></a>
  <?php endforeach; ?>
</div>

<?php if (!$workTablesReady): ?>
  <div class="card">
    <h2>One-time setup needed</h2>
    <p class="sub">Our Work stores its categories and projects in database tables that don't exist on this site yet. Open <strong>phpMyAdmin</strong> (in cPanel), select your database, go to the <strong>SQL</strong> tab, paste the code below, and click Go. This only needs to be done once — nothing else on the site is affected.</p>
    <textarea readonly rows="20" style="font-family:monospace;font-size:12.5px;white-space:pre" onclick="this.select()">CREATE TABLE IF NOT EXISTS `work_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `slug` VARCHAR(140) NOT NULL,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `work_projects` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) DEFAULT NULL,
  `title` VARCHAR(160) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `link` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `work_categories` (`name`, `slug`, `sort_order`) VALUES
('Web Development', 'web-development', 1),
('Logo Designs', 'logo-designs', 2),
('SEO Projects', 'seo-projects', 3);</textarea>
    <p class="muted" style="margin-top:10px">Once that's run, refresh this page and the Our Work editor will appear here.</p>
  </div>
<?php elseif ($tab === 'categories'): ?>
  <div class="card">
    <h2>Categories</h2>
    <p class="sub">The filter tabs shown at the top of the Our Work page. Deleting a category doesn't delete its projects — they just become uncategorised.</p>
  </div>
  <?php foreach ($categories as $idx => $cat): ?>
  <div class="card">
    <div class="rh" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
      <span class="pill"><?= e($cat['name']) ?></span>
      <div style="display:flex;gap:6px">
        <form method="post" action="?tab=categories" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $cat['id'] ?>"><input type="hidden" name="dir" value="up"><button class="btn btn-ghost btn-sm" <?= $idx === 0 ? 'disabled' : '' ?>>↑</button></form>
        <form method="post" action="?tab=categories" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $cat['id'] ?>"><input type="hidden" name="dir" value="down"><button class="btn btn-ghost btn-sm" <?= $idx === count($categories) - 1 ? 'disabled' : '' ?>>↓</button></form>
        <form method="post" action="?tab=categories" style="display:inline" onsubmit="return confirm('Delete this category? Projects in it will become uncategorised, not deleted.')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $cat['id'] ?>"><button class="btn btn-danger btn-sm">Delete</button></form>
      </div>
    </div>
    <form method="post" action="?tab=categories">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" value="<?= $cat['id'] ?>">
      <div class="field"><label>Category name</label><input type="text" name="name" value="<?= e($cat['name']) ?>"></div>
      <button class="btn btn-sm" type="submit">Save changes</button>
    </form>
  </div>
  <?php endforeach; ?>
  <div class="card" style="border-style:dashed">
    <h2>Add category</h2>
    <form method="post" action="?tab=categories">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="add">
      <div class="field"><label>Category name</label><input type="text" name="name" placeholder="e.g. Web Development"></div>
      <button class="btn" type="submit">+ Add category</button>
    </form>
  </div>

<?php elseif ($tab === 'projects'): ?>
  <div class="card">
    <h2>Projects</h2>
    <p class="sub">Each project's image, name, description, link and category are all managed together. Use the arrows to reorder.</p>
    <form method="get" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
      <input type="hidden" name="tab" value="projects">
      <div class="field" style="margin:0;flex:1;min-width:180px">
        <label>Search by name</label>
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search projects…">
      </div>
      <div class="field" style="margin:0;min-width:180px">
        <label>Category</label>
        <select name="cat">
          <option value="">All categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= (string) $cat['id'] === $catFilter ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button class="btn btn-ghost btn-sm" type="submit">Filter</button>
      <?php if ($q !== '' || $catFilter !== ''): ?><a class="btn btn-ghost btn-sm" href="?tab=projects">Clear</a><?php endif; ?>
    </form>
  </div>

  <?php if (!$categories): ?>
  <div class="card">
    <p class="sub" style="margin:0">Add at least one category first, then you can add projects to it.</p>
  </div>
  <?php endif; ?>

  <?php foreach ($projects as $idx => $p): ?>
  <div class="card">
    <div class="rh" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
      <span class="pill"><?= e($p['category_name'] ?: 'Uncategorised') ?></span>
      <div style="display:flex;gap:6px">
        <form method="post" action="?tab=projects" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $p['id'] ?>"><input type="hidden" name="dir" value="up"><button class="btn btn-ghost btn-sm" <?= $idx === 0 ? 'disabled' : '' ?>>↑</button></form>
        <form method="post" action="?tab=projects" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $p['id'] ?>"><input type="hidden" name="dir" value="down"><button class="btn btn-ghost btn-sm" <?= $idx === count($projects) - 1 ? 'disabled' : '' ?>>↓</button></form>
        <form method="post" action="?tab=projects" style="display:inline" onsubmit="return confirm('Delete this project?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="btn btn-danger btn-sm">Delete</button></form>
      </div>
    </div>
    <form method="post" action="?tab=projects" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" value="<?= $p['id'] ?>">
      <div class="img-field" style="margin-bottom:18px">
        <?php if ($p['image']): ?>
          <img class="thumb" src="../<?= e($p['image']) ?>" alt="">
        <?php else: ?>
          <div class="thumb" style="display:grid;place-items:center;color:#aaa;font-size:11px">none</div>
        <?php endif; ?>
        <div style="flex:1">
          <label>Project image (4:5 works best)</label>
          <input type="file" name="image" accept="image/*">
          <div class="muted" style="margin-top:6px">Leave empty to keep the current image.</div>
        </div>
      </div>
      <div class="two">
        <div class="field"><label>Project name</label><input type="text" name="title" value="<?= e($p['title']) ?>"></div>
        <div class="field">
          <label>Category</label>
          <select name="category_id">
            <option value="">Uncategorised</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= (int) $p['category_id'] === (int) $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field"><label>Short description</label><textarea name="description" rows="2"><?= e($p['description']) ?></textarea></div>
      <div class="field"><label>Project link</label><input type="text" name="link" value="<?= e($p['link']) ?>" placeholder="https://…"></div>
      <button class="btn btn-sm" type="submit">Save changes</button>
    </form>
  </div>
  <?php endforeach; ?>
  <?php if ($categories): ?>
  <div class="card" style="border-style:dashed">
    <h2>Add project</h2>
    <form method="post" action="?tab=projects" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="add">
      <div class="field"><label>Project image (4:5 works best)</label><input type="file" name="image" accept="image/*"></div>
      <div class="two">
        <div class="field"><label>Project name</label><input type="text" name="title" placeholder="Project name"></div>
        <div class="field">
          <label>Category</label>
          <select name="category_id" required>
            <option value="">Select a category</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field"><label>Short description</label><textarea name="description" rows="2" placeholder="A one or two line summary…"></textarea></div>
      <div class="field"><label>Project link</label><input type="text" name="link" placeholder="https://…"></div>
      <button class="btn" type="submit">+ Add project</button>
    </form>
  </div>
  <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
