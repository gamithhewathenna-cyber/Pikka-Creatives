<?php
require_once __DIR__ . '/config.php';

/* Escape output for HTML */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/* Load all page_content rows into an associative array [key => value] */
function get_content() {
    static $cache = null;
    if ($cache !== null) return $cache;
    $cache = [];
    $res = mysqli_query(db(), "SELECT content_key, content_value FROM page_content");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $cache[$row['content_key']] = $row['content_value'];
        }
    }
    return $cache;
}

/* Get a single content value with fallback */
function c($key, $default = '') {
    $data = get_content();
    return isset($data[$key]) && $data[$key] !== '' ? $data[$key] : $default;
}

/* Load site_settings [key => value] */
function get_settings() {
    static $cache = null;
    if ($cache !== null) return $cache;
    $cache = [];
    $res = mysqli_query(db(), "SELECT setting_key, setting_value FROM site_settings");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $cache;
}

function s($key, $default = '') {
    $data = get_settings();
    return isset($data[$key]) && $data[$key] !== '' ? $data[$key] : $default;
}

/* Render the logo mark: an uploaded image if set (colour for header, white for
   footer), otherwise the "✳ {text}." fallback. $logoText is the already
   site_name-defaulted logo text the caller already fetched. */
function brand_mark($logoText, $isFooter = false) {
    $img = s($isFooter ? 'logo_image_white' : 'logo_image');
    if ($img) {
        // Header logo is above the fold and needed immediately; footer logo can lazy-load.
        $loading = $isFooter ? 'lazy' : 'eager';
        return '<img src="' . e($img) . '" alt="' . e($logoText) . '" class="brand-logo-img" loading="' . $loading . '" decoding="async">';
    }
    return '<span class="dot">✳</span>' . e($logoText) . '<span class="accent">.</span>';
}

/* Split a content field into paragraphs (blank line, or double-space after a full stop) */
function paragraphs($text) {
    $out = [];
    foreach (preg_split('/\n{2,}|(?<=\.)\s{2,}/', (string)$text) as $p) {
        $p = trim($p);
        if ($p !== '') $out[] = $p;
    }
    return $out;
}

/* Fetch rows from a list table ordered by sort_order */
function get_rows($table) {
    $allowed = ['services', 'why_reasons', 'process_steps', 'stats', 'hero_slides', 'team_members'];
    if (!in_array($table, $allowed, true)) return [];
    $extra = ($table === 'services') ? ' WHERE is_active = 1' : '';
    $res = mysqli_query(db(), "SELECT * FROM `$table`$extra ORDER BY sort_order ASC, id ASC");
    $out = [];
    if ($res) while ($row = mysqli_fetch_assoc($res)) $out[] = $row;
    return $out;
}

/* Turn a category name into a URL/JS-safe slug, e.g. "Web Development" -> "web-development" */
function slugify($text) {
    $text = strtolower(trim((string)$text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-') ?: 'category';
}

/* Our Work — categories ordered for display */
function get_work_categories() {
    $out = [];
    $res = mysqli_query(db(), "SELECT * FROM work_categories ORDER BY sort_order ASC, id ASC");
    if ($res) while ($row = mysqli_fetch_assoc($res)) $out[] = $row;
    return $out;
}

/* Our Work — projects joined with their category's slug/name, ordered for display.
   Pass a category id to only fetch that category's projects (admin use). */
function get_work_projects($categoryId = null) {
    $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM work_projects p
            LEFT JOIN work_categories c ON c.id = p.category_id";
    if ($categoryId !== null) $sql .= " WHERE p.category_id = " . (int) $categoryId;
    $sql .= " ORDER BY p.sort_order ASC, p.id ASC";
    $out = [];
    $res = mysqli_query(db(), $sql);
    if ($res) while ($row = mysqli_fetch_assoc($res)) $out[] = $row;
    return $out;
}

/* A shuffled sample of projects for the homepage "Latest Work" strip. */
function get_random_work_projects($limit = 7) {
    $limit = (int) $limit;
    $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM work_projects p
            LEFT JOIN work_categories c ON c.id = p.category_id
            ORDER BY RAND() LIMIT $limit";
    $out = [];
    $res = mysqli_query(db(), $sql);
    if ($res) while ($row = mysqli_fetch_assoc($res)) $out[] = $row;
    return $out;
}

/* Shared front-end maintenance-mode gate. Shows the "under maintenance" page
   and exits for everyone except a logged-in admin; returns whether the
   current visitor is a logged-in admin otherwise. Call at the top of every
   public-facing page. */
function maintenance_gate() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $is_admin = !empty($_SESSION['admin_id']);
    if ($is_admin || s('maintenance_mode') !== '1') return $is_admin;

    http_response_code(503);
    header('Retry-After: 3600');
    $accent = s('accent_color', '#F1592A');
    $logo   = s('logo_text', 'Pikka');
    $msg    = s('maintenance_message', "We're currently making some improvements. Please check back soon.");
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e(s('site_name', 'Pikka Creatives')) ?> — Under Maintenance</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{--accent:<?= e($accent) ?>}
  *{box-sizing:border-box}
  body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#0d0d0f;color:#f5f5f5;font-family:Inter,sans-serif;padding:24px;text-align:center}
  .mark{font-family:Sora,sans-serif;font-size:2rem;font-weight:700;margin-bottom:1.2rem}
  .mark span{color:var(--accent)}
  .card{max-width:480px}
  h1{font-family:Sora,sans-serif;font-size:1.6rem;margin:0 0 .8rem}
  p{color:#b8b8bd;line-height:1.6;margin:0;white-space:pre-line}
  .dot{color:var(--accent);margin-right:.4rem}
</style>
</head>
<body>
  <div class="card">
    <div class="mark"><span class="dot">✳</span><?= e($logo) ?><span>.</span></div>
    <h1>We'll be right back</h1>
    <p><?= e($msg) ?></p>
  </div>
</body>
</html>
<?php
    exit;
}
