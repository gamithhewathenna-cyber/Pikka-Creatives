<?php
require_once __DIR__ . '/auth.php';
require_login();

$tabs = [
    'about'   => ['label' => 'About Us'],
    'contact' => ['label' => 'Contact Us'],
];
$tab = $_GET['tab'] ?? 'about';
if (!array_key_exists($tab, $tabs)) $tab = 'about';

$current_page = 'page-content';
$page_title = $tabs[$tab]['label'];

$who_body_default = "Pikka Creative is a New Zealand-based design and content studio on a mission to help local brands punch above their weight. We create branding, websites, social content, and packaging for businesses across Aotearoa — from small startups finding their feet to established names ready for a fresh look.\n\nGreat ideas can begin anywhere, and they should never be limited by a postcode or the size of a budget. Pikka Creatives exists to give every New Zealand business access to design that feels thoughtful, confident and unmistakably its own. That belief is at the heart of everything we create.";
$story_body_default = "Every business has a story, but sometimes it takes a fresh perspective to see what makes it special. Priyal's understanding of New Zealand businesses, together with his long-standing passion for photography, brings a local eye for detail, people and the moments that make a brand feel real.\n\nThat perspective comes together with Gamith and Creativelements' experience in creativity, design, web development and digital delivery. What began as a conversation between two people with different strengths became Pikka Creatives: a shared vision to bring thoughtful, high-quality creative work within reach of businesses across New Zealand.";
$approach_body_default = "Good creative begins with a good conversation. From the first chat to final delivery, we keep communication open, updates clear and jargon out of the way. We listen first, create with purpose and refine the work together.\n\nYou will have a creative partner in your corner who understands your goals, values your ideas and genuinely cares about helping your business grow.";

$about_groups = [
    'Hero' => [
        'about_hero_eyebrow'     => ['Eyebrow', 'text', 'About Pikka Creatives'],
        'about_hero_headline'    => ['Headline', 'text', 'A creative partner for New Zealand businesses with big ideas.'],
        'about_hero_subheadline' => ['Sub-headline', 'textarea', 'Pikka Creative helps local brands look sharp, sound clear, and stand out — built on the belief that great design should belong to every business.'],
    ],
    'Who We Are' => [
        'about_who_eyebrow' => ['Eyebrow', 'text', 'Kia ora'],
        'about_who_heading' => ['Heading', 'text', 'A creative studio built for Aotearoa.'],
        'about_who_body'    => ['Body (separate paragraphs with a blank line)', 'textarea', $who_body_default],
    ],
    'Our Story' => [
        'about_story_eyebrow' => ['Eyebrow', 'text', 'Our story'],
        'about_story_heading' => ['Heading', 'text', 'Where business understanding meets creative vision.'],
        'about_story_body'    => ['Body (separate paragraphs with a blank line)', 'textarea', $story_body_default],
    ],
    'Values' => [
        'about_values_eyebrow' => ['Eyebrow', 'text', 'Our values'],
        'about_values_heading' => ['Heading', 'text', 'What guides everything we create.'],
        'about_value1_title'   => ['Value 1 — title', 'text', 'People and place matter'],
        'about_value1_desc'    => ['Value 1 — description', 'textarea', 'We create with your business, your customers and the market in mind. The best work begins by understanding the people it needs to reach.'],
        'about_value2_title'   => ['Value 2 — title', 'text', 'Honesty builds trust'],
        'about_value2_desc'    => ['Value 2 — description', 'textarea', 'Clear communication, fair pricing and no hidden surprises. We share our thinking openly and make sure you always understand what happens next.'],
        'about_value3_title'   => ['Value 3 — title', 'text', 'Care lives in the details'],
        'about_value3_desc'    => ['Value 3 — description', 'textarea', 'The smallest choices can shape how people see and remember a brand. We give every detail the attention it deserves.'],
        'about_value4_title'   => ['Value 4 — title', 'text', 'Created for the journey'],
        'about_value4_desc'    => ['Value 4 — description', 'textarea', 'We build flexible brands, websites and digital solutions that can evolve with your business and continue working well into the future.'],
    ],
    'Our Approach' => [
        'about_approach_eyebrow' => ['Eyebrow', 'text', 'Our approach'],
        'about_approach_heading' => ['Heading', 'text', 'Collaborative, clear and refreshingly straightforward.'],
        'about_approach_body'    => ['Body (separate paragraphs with a blank line)', 'textarea', $approach_body_default],
    ],
    'Why Work With Us' => [
        'about_why_eyebrow'   => ['Eyebrow', 'text', 'Why Pikka Creatives'],
        'about_why_heading'   => ['Heading', 'text', 'Creative partnership you can rely on.'],
        'about_benefit1_title'=> ['Benefit 1 — title', 'text', 'We understand your market'],
        'about_benefit1_desc' => ['Benefit 1 — description', 'textarea', 'We take time to understand your business, your customers and what will connect with your New Zealand audience.'],
        'about_benefit2_title'=> ['Benefit 2 — title', 'text', 'We work closely with you'],
        'about_benefit2_desc' => ['Benefit 2 — description', 'textarea', 'Clear communication and genuine collaboration keep you involved and informed throughout the project.'],
        'about_benefit3_title'=> ['Benefit 3 — title', 'text', 'Everything works together'],
        'about_benefit3_desc' => ['Benefit 3 — description', 'textarea', 'Branding, websites, digital marketing, social content and print are shaped around one clear and consistent vision.'],
        'about_benefit4_title'=> ['Benefit 4 — title', 'text', 'We think beyond today'],
        'about_benefit4_desc' => ['Benefit 4 — description', 'textarea', 'We create flexible solutions that can evolve as your business, audience and ambitions grow.'],
    ],
    'Team' => [
        'about_team_eyebrow' => ['Eyebrow', 'text', 'Our team'],
        'about_team_heading' => ['Heading', 'text', 'The people behind Pikka Creative.'],
        'about_team_intro'   => ['Intro line', 'textarea', "A small, dedicated team of Kiwi creatives who care about doing things well. When you work with us, you work directly with the people making your ideas real."],
        'about_team_closing' => ['Closing line (the email address is added automatically, from Settings)', 'textarea', "Want to join the team? We're always keen to hear from talented Kiwi creatives — flick us an email at"],
    ],
    'Call To Action' => [
        'about_cta_eyebrow' => ['Eyebrow', 'text', "Let's work together"],
        'about_cta_heading' => ['Heading', 'text', "Like how we think? Let's talk."],
        'about_cta_body'    => ['Body', 'textarea', "Kia ora — thanks for getting to know us. If you're a New Zealand business looking for a creative partner who genuinely gets your market, we'd love to hear from you."],
    ],
];

$about_images = [
    'about_banner_image_1' => 'Who We Are section — image',
    'about_approach_image' => 'Our Approach section — image',
];

// team_members is only on sites that have run the latest sql/pikka_db.sql.
// Guard against it so a missing table shows a clear message instead of a fatal error.
$teamTableReady = (bool) mysqli_query(db(), "SELECT 1 FROM team_members LIMIT 1");

$contact_groups = [
    'Hero' => [
        'contact_hero_eyebrow'  => ['Eyebrow', 'text', 'Contact Us'],
        'contact_hero_headline' => ['Headline', 'text', "Let's make something."],
        'contact_hero_lead'     => ['Lead paragraph', 'textarea', "Whether you've got a clear brief or just a spark of an idea, we'd love to hear from you. Tell us about your project and we'll get back to you within two business days."],
    ],
    'Details' => [
        'contact_location' => ['Location', 'text', 'New Zealand — working with clients nationwide'],
    ],
    'Follow us' => [
        'contact_social_instagram' => ['Instagram URL', 'text', '#'],
        'contact_social_facebook'  => ['Facebook URL', 'text', '#'],
        'contact_social_linkedin'  => ['LinkedIn URL', 'text', '#'],
    ],
    'Closing' => [
        'contact_closing' => ['Closing line (the email address is added automatically, from Settings)', 'textarea', "Prefer a chat? Flick us an email and we'll set up a time. No pressure, no jargon — just a friendly kōrero about your ideas:"],
    ],
];

$groups = $tab === 'about' ? $about_groups : $contact_groups;

/* ---------------- POST handling ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $formType = $_POST['form_type'] ?? '';

    if ($formType === 'text') {
        $fields = $_POST['field'] ?? [];
        $stmt = mysqli_prepare(db(), "UPDATE page_content SET content_value = ? WHERE content_key = ?");
        foreach ($fields as $key => $val) {
            $val = (string)$val;
            mysqli_stmt_bind_param($stmt, 'ss', $val, $key);
            mysqli_stmt_execute($stmt);
        }

        $sectionImageKey = $_POST['section_image_key'] ?? '';
        if ($tab === 'about' && array_key_exists($sectionImageKey, $about_images)) {
            if (!empty($_POST['remove_image'])) {
                $st = mysqli_prepare(db(), "UPDATE page_content SET content_value='' WHERE content_key=?");
                mysqli_stmt_bind_param($st, 's', $sectionImageKey); mysqli_stmt_execute($st);
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
                    $name = $sectionImageKey . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($f['tmp_name'], $uploadDir . $name)) {
                        $path = 'uploads/' . $name;
                        $st = mysqli_prepare(db(), "UPDATE page_content SET content_value=? WHERE content_key=?");
                        mysqli_stmt_bind_param($st, 'ss', $path, $sectionImageKey); mysqli_stmt_execute($st);
                    } else { flash('Could not save the uploaded file. Check the uploads/ folder permissions (755).'); }
                }
            }
        }
        flash('Content updated successfully.');
    }
    elseif ($formType === 'team' && $tab === 'about' && !$teamTableReady) {
        flash('The Team members list needs a one-time database update before it can be used — see the note on this page for the SQL to run.');
    }
    elseif ($formType === 'team' && $tab === 'about') {
        $action = $_POST['action'] ?? '';

        if ($action === 'add' || $action === 'edit') {
            $name = trim($_POST['name'] ?? '');
            $role = trim($_POST['role'] ?? '');
            $bio  = trim($_POST['bio'] ?? '');

            $photoPath = null; // null = leave existing photo untouched (edit only)
            if (!empty($_FILES['photo']['name'])) {
                $uploadDir = __DIR__ . '/../uploads/';
                if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);
                $f = $_FILES['photo'];
                $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif'];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                $finfo = @getimagesize($f['tmp_name']);
                if ($f['error'] !== 0) { flash('Upload error.'); }
                elseif (!isset($allowed[$ext]) || !$finfo) { flash('Please upload a JPG, PNG, WEBP or GIF image.'); }
                elseif ($f['size'] > 5 * 1024 * 1024) { flash('Image must be under 5 MB.'); }
                else {
                    $fname = 'team_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                    if (move_uploaded_file($f['tmp_name'], $uploadDir . $fname)) {
                        $photoPath = 'uploads/' . $fname;
                    } else { flash('Could not save the uploaded photo. Check the uploads/ folder permissions (755).'); }
                }
            }

            if ($action === 'add') {
                $ord = mysqli_fetch_assoc(mysqli_query(db(), "SELECT COALESCE(MAX(sort_order),0)+1 n FROM team_members"))['n'];
                $photo = $photoPath ?? '';
                $stmt = mysqli_prepare(db(), "INSERT INTO team_members (name, role, bio, photo, sort_order) VALUES (?,?,?,?,?)");
                mysqli_stmt_bind_param($stmt, 'ssssi', $name, $role, $bio, $photo, $ord);
                mysqli_stmt_execute($stmt);
                flash('Team member added.');
            } else {
                $id = (int)($_POST['id'] ?? 0);
                if ($photoPath !== null) {
                    $stmt = mysqli_prepare(db(), "UPDATE team_members SET name=?, role=?, bio=?, photo=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt, 'ssssi', $name, $role, $bio, $photoPath, $id);
                } else {
                    $stmt = mysqli_prepare(db(), "UPDATE team_members SET name=?, role=?, bio=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt, 'sssi', $name, $role, $bio, $id);
                }
                mysqli_stmt_execute($stmt);
                flash('Team member updated.');
            }
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = mysqli_prepare(db(), "DELETE FROM team_members WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            flash('Team member removed.');
        }
        elseif ($action === 'move') {
            $id = (int)($_POST['id'] ?? 0);
            $dir = $_POST['dir'] === 'up' ? 'up' : 'down';
            $rows = [];
            $r = mysqli_query(db(), "SELECT id, sort_order FROM team_members ORDER BY sort_order ASC, id ASC");
            while ($x = mysqli_fetch_assoc($r)) $rows[] = $x;
            for ($i = 0; $i < count($rows); $i++) {
                if ((int)$rows[$i]['id'] === $id) {
                    $j = $dir === 'up' ? $i - 1 : $i + 1;
                    if ($j >= 0 && $j < count($rows)) {
                        $a = $rows[$i]['id']; $b = $rows[$j]['id'];
                        $oa = $rows[$i]['sort_order']; $ob = $rows[$j]['sort_order'];
                        mysqli_query(db(), "UPDATE team_members SET sort_order=$ob WHERE id=$a");
                        mysqli_query(db(), "UPDATE team_members SET sort_order=$oa WHERE id=$b");
                    }
                    break;
                }
            }
        }
    }

    header('Location: page-content.php?tab=' . $tab); exit;
}

/* ---------------- Make sure every row this tab needs actually exists ---------------- */
$section_label = $tab === 'about' ? 'About' : 'Contact';
$seedStmt = mysqli_prepare(db(), "INSERT IGNORE INTO page_content (content_key, content_value, section, label, field_type) VALUES (?, ?, ?, ?, ?)");
foreach ($groups as $groupLabel => $fields) {
    foreach ($fields as $key => $meta) {
        [$label, $type, $default] = $meta;
        mysqli_stmt_bind_param($seedStmt, 'sssss', $key, $default, $section_label, $label, $type);
        mysqli_stmt_execute($seedStmt);
    }
}
if ($tab === 'about') {
    foreach ($about_images as $key => $label) {
        $empty = '';
        $imgType = 'image';
        mysqli_stmt_bind_param($seedStmt, 'sssss', $key, $empty, $section_label, $label, $imgType);
        mysqli_stmt_execute($seedStmt);
    }
}

function render_section_image_field($current, $label = 'Section image') {
    echo '<div class="img-field" style="margin-bottom:20px">';
    if ($current) {
        echo '<img class="thumb" src="../' . e($current) . '" alt="">';
    } else {
        echo '<div class="thumb" style="display:grid;place-items:center;color:#aaa;font-size:11px">none</div>';
    }
    echo '<div style="flex:1"><label>' . e($label) . '</label><input type="file" name="image" accept="image/*">';
    echo '<div class="muted" style="margin-top:6px">' . ($current ? e($current) : 'No image set');
    if ($current) {
        echo '&nbsp;&middot;&nbsp;<label style="display:inline;font-weight:400"><input type="checkbox" name="remove_image" value="1" style="width:auto;display:inline;vertical-align:middle"> Remove current image</label>';
    }
    echo '</div></div></div>';
}

function render_group_fields($fields) {
    foreach ($fields as $key => $meta) {
        [$label, $type, $default] = $meta;
        $val = c($key, $default);
        echo '<div class="field"><label>' . e($label) . '</label>';
        if ($type === 'textarea') {
            echo '<textarea name="field[' . e($key) . ']" rows="3">' . e($val) . '</textarea>';
        } else {
            echo '<input type="text" name="field[' . e($key) . ']" value="' . e($val) . '">';
        }
        echo '</div>';
    }
}

$teamRows = $tab === 'about' ? get_rows('team_members') : [];

require __DIR__ . '/header.php';
?>
<div class="card">
  <p class="sub">Email and phone number are shared across the site and stay managed from <a href="settings.php">Settings</a>.</p>
</div>

<?php if ($tab !== 'about'): ?>

<form method="post" action="?tab=<?= e($tab) ?>">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_type" value="text">
  <?php foreach ($groups as $groupLabel => $fields): ?>
    <div class="card">
      <h2><?= e($groupLabel) ?></h2>
      <?php render_group_fields($fields); ?>
    </div>
  <?php endforeach; ?>
  <button class="btn" type="submit">Save all changes</button>
</form>

<?php else: ?>

<form method="post" action="?tab=about">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_type" value="text">
  <div class="card">
    <h2>Hero</h2>
    <?php render_group_fields($groups['Hero']); ?>
  </div>
  <button class="btn" type="submit">Save changes</button>
</form>

<form method="post" action="?tab=about" enctype="multipart/form-data">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_type" value="text">
  <input type="hidden" name="section_image_key" value="about_banner_image_1">
  <div class="card">
    <h2>Who We Are</h2>
    <p class="sub">Edit the copy and image for this section together.</p>
    <?php render_section_image_field(c('about_banner_image_1')); ?>
    <?php render_group_fields($groups['Who We Are']); ?>
    <button class="btn btn-sm" type="submit">Save changes</button>
  </div>
</form>

<form method="post" action="?tab=about">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_type" value="text">
  <?php foreach (['Our Story', 'Values'] as $groupLabel): ?>
    <div class="card">
      <h2><?= e($groupLabel) ?></h2>
      <?php render_group_fields($groups[$groupLabel]); ?>
    </div>
  <?php endforeach; ?>
  <button class="btn" type="submit">Save all changes</button>
</form>

<form method="post" action="?tab=about" enctype="multipart/form-data">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_type" value="text">
  <input type="hidden" name="section_image_key" value="about_approach_image">
  <div class="card">
    <h2>Our Approach</h2>
    <p class="sub">Edit the copy and image for this section together.</p>
    <?php render_section_image_field(c('about_approach_image')); ?>
    <?php render_group_fields($groups['Our Approach']); ?>
    <button class="btn btn-sm" type="submit">Save changes</button>
  </div>
</form>

<form method="post" action="?tab=about">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_type" value="text">
  <?php foreach (['Why Work With Us'] as $groupLabel): ?>
    <div class="card">
      <h2><?= e($groupLabel) ?></h2>
      <?php render_group_fields($groups[$groupLabel]); ?>
    </div>
  <?php endforeach; ?>
  <button class="btn" type="submit">Save all changes</button>
</form>

<form method="post" action="?tab=about">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_type" value="text">
  <div class="card">
    <h2>Team</h2>
    <p class="sub">Heading and intro copy for the team section. Add, edit, remove and reorder the actual team members below.</p>
    <?php render_group_fields($groups['Team']); ?>
    <button class="btn btn-sm" type="submit">Save changes</button>
  </div>
</form>

<?php if (!$teamTableReady): ?>
<div class="card">
  <h2>Team members — one-time setup needed</h2>
  <p class="sub">The team member list stores its data in a database table that doesn't exist on this site yet. Open <strong>phpMyAdmin</strong> (in cPanel), select your database, go to the <strong>SQL</strong> tab, paste the code below, and click Go. This only needs to be done once — nothing else on the site is affected.</p>
  <textarea readonly rows="12" style="font-family:monospace;font-size:12.5px;white-space:pre" onclick="this.select()">CREATE TABLE IF NOT EXISTS `team_members` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) DEFAULT NULL,
  `role` VARCHAR(120) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `team_members` (`name`, `role`, `bio`, `photo`, `sort_order`) VALUES
('[Add name]', 'Founder & Creative Director', 'Add a short 1–2 line bio — background, what they do, a personal touch.', '', 1),
('[Add name]', 'Designer', 'Add a short 1–2 line bio — background, what they do, a personal touch.', '', 2),
('[Add name]', 'Web Developer', 'Add a short 1–2 line bio — background, what they do, a personal touch.', '', 3),
('[Add name]', 'Content & Social', 'Add a short 1–2 line bio — background, what they do, a personal touch.', '', 4);</textarea>
  <p class="muted" style="margin-top:10px">Once that's run, refresh this page and the team member editor will appear here.</p>
</div>
<?php else: ?>
<div class="card">
  <h2>Team members</h2>
  <p class="sub">Each member's photo, name, role and bio are all managed together here. Use the arrows to reorder, or remove someone who's left.</p>
</div>
<?php foreach ($teamRows as $idx => $tm): ?>
<div class="card">
  <div class="rh" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
    <span class="pill">Member <?= $idx + 1 ?></span>
    <div style="display:flex;gap:6px">
      <form method="post" action="?tab=about" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="form_type" value="team"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $tm['id'] ?>"><input type="hidden" name="dir" value="up"><button class="btn btn-ghost btn-sm" <?= $idx === 0 ? 'disabled' : '' ?>>↑</button></form>
      <form method="post" action="?tab=about" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="form_type" value="team"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $tm['id'] ?>"><input type="hidden" name="dir" value="down"><button class="btn btn-ghost btn-sm" <?= $idx === count($teamRows) - 1 ? 'disabled' : '' ?>>↓</button></form>
      <form method="post" action="?tab=about" style="display:inline" onsubmit="return confirm('Remove this team member?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="form_type" value="team"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $tm['id'] ?>"><button class="btn btn-danger btn-sm">Remove</button></form>
    </div>
  </div>
  <form method="post" action="?tab=about" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="form_type" value="team">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?= $tm['id'] ?>">
    <div class="img-field" style="margin-bottom:18px">
      <?php if ($tm['photo']): ?>
        <img class="thumb" src="../<?= e($tm['photo']) ?>" alt="">
      <?php else: ?>
        <div class="thumb" style="display:grid;place-items:center;color:#aaa;font-size:11px">none</div>
      <?php endif; ?>
      <div style="flex:1">
        <label>Photo</label>
        <input type="file" name="photo" accept="image/*">
        <div class="muted" style="margin-top:6px">Leave empty to keep the current photo.</div>
      </div>
    </div>
    <div class="two">
      <div class="field"><label>Name</label><input type="text" name="name" value="<?= e($tm['name']) ?>"></div>
      <div class="field"><label>Role</label><input type="text" name="role" value="<?= e($tm['role']) ?>"></div>
    </div>
    <div class="field"><label>Bio</label><textarea name="bio" rows="2"><?= e($tm['bio']) ?></textarea></div>
    <button class="btn btn-sm" type="submit">Save changes</button>
  </form>
</div>
<?php endforeach; ?>
<div class="card" style="border-style:dashed">
  <h2>Add team member</h2>
  <form method="post" action="?tab=about" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="form_type" value="team">
    <input type="hidden" name="action" value="add">
    <div class="field"><label>Photo</label><input type="file" name="photo" accept="image/*"></div>
    <div class="two">
      <div class="field"><label>Name</label><input type="text" name="name" placeholder="[Add name]"></div>
      <div class="field"><label>Role</label><input type="text" name="role" placeholder="e.g. Designer"></div>
    </div>
    <div class="field"><label>Bio</label><textarea name="bio" rows="2" placeholder="Add a short 1–2 line bio…"></textarea></div>
    <button class="btn" type="submit">+ Add team member</button>
  </form>
</div>
<?php endif; ?>

<form method="post" action="?tab=about">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="form_type" value="text">
  <div class="card">
    <h2>Call To Action</h2>
    <?php render_group_fields($groups['Call To Action']); ?>
    <button class="btn btn-sm" type="submit">Save changes</button>
  </div>
</form>

<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
