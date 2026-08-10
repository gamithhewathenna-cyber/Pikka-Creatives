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

/* Fetch rows from a list table ordered by sort_order */
function get_rows($table) {
    $allowed = ['services', 'why_reasons', 'process_steps', 'stats'];
    if (!in_array($table, $allowed, true)) return [];
    $extra = ($table === 'services') ? ' WHERE is_active = 1' : '';
    $res = mysqli_query(db(), "SELECT * FROM `$table`$extra ORDER BY sort_order ASC, id ASC");
    $out = [];
    if ($res) while ($row = mysqli_fetch_assoc($res)) $out[] = $row;
    return $out;
}
