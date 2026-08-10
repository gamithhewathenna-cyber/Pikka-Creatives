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
        'about_team1_name'   => ['Member 1 — name', 'text', '[Add name]'],
        'about_team1_role'   => ['Member 1 — role', 'text', 'Founder & Creative Director'],
        'about_team1_bio'    => ['Member 1 — bio', 'textarea', 'Add a short 1–2 line bio — background, what they do, a personal touch.'],
        'about_team2_name'   => ['Member 2 — name', 'text', '[Add name]'],
        'about_team2_role'   => ['Member 2 — role', 'text', 'Designer'],
        'about_team2_bio'    => ['Member 2 — bio', 'textarea', 'Add a short 1–2 line bio — background, what they do, a personal touch.'],
        'about_team3_name'   => ['Member 3 — name', 'text', '[Add name]'],
        'about_team3_role'   => ['Member 3 — role', 'text', 'Web Developer'],
        'about_team3_bio'    => ['Member 3 — bio', 'textarea', 'Add a short 1–2 line bio — background, what they do, a personal touch.'],
        'about_team4_name'   => ['Member 4 — name', 'text', '[Add name]'],
        'about_team4_role'   => ['Member 4 — role', 'text', 'Content & Social'],
        'about_team4_bio'    => ['Member 4 — bio', 'textarea', 'Add a short 1–2 line bio — background, what they do, a personal touch.'],
        'about_team_closing' => ['Closing line (the email address is added automatically, from Settings)', 'textarea', "Want to join the team? We're always keen to hear from talented Kiwi creatives — flick us an email at"],
    ],
    'Call To Action' => [
        'about_cta_eyebrow' => ['Eyebrow', 'text', "Let's work together"],
        'about_cta_heading' => ['Heading', 'text', "Like how we think? Let's talk."],
        'about_cta_body'    => ['Body', 'textarea', "Kia ora — thanks for getting to know us. If you're a New Zealand business looking for a creative partner who genuinely gets your market, we'd love to hear from you."],
    ],
];

$about_images = [
    'about_banner_image_1' => 'Top banner — image 1',
    'about_banner_image_2' => 'Top banner — image 2',
    'about_team1_photo'    => 'Team photo — member 1',
    'about_team2_photo'    => 'Team photo — member 2',
    'about_team3_photo'    => 'Team photo — member 3',
    'about_team4_photo'    => 'Team photo — member 4',
];

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
        flash('Content updated successfully.');
    }
    elseif ($formType === 'image' && $tab === 'about') {
        $key = $_POST['img_key'] ?? '';
        if (!array_key_exists($key, $about_images)) {
            flash('Unknown image slot.');
        } elseif (($_POST['do'] ?? '') === 'remove') {
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

require __DIR__ . '/header.php';
?>
<div class="card">
  <p class="sub">Email and phone number are shared across the site and stay managed from <a href="settings.php">Settings</a>.</p>
</div>

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

<?php if ($tab === 'about'): ?>
  <div class="card">
    <h2>Images</h2>
    <p class="sub">The two banner photos at the top of the page, and each team member's photo.</p>
  </div>
  <?php foreach ($about_images as $key => $label):
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
        <form method="post" action="?tab=about" enctype="multipart/form-data" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="form_type" value="image">
          <input type="hidden" name="img_key" value="<?= e($key) ?>">
          <input type="file" name="image" accept="image/*" required style="max-width:280px">
          <button class="btn btn-sm" type="submit">Upload</button>
        </form>
        <?php if ($current): ?>
        <form method="post" action="?tab=about" style="margin-top:8px" onsubmit="return confirm('Remove this image?')">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="form_type" value="image">
          <input type="hidden" name="img_key" value="<?= e($key) ?>">
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
