<?php
require_once __DIR__ . '/../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

function require_login() {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php'); exit;
    }
}

/* Whether admin_users has the role column yet — sites that haven't run the
   latest sql/pikka_db.sql migration won't have it. Everyone behaves as a
   full admin until it's added. */
function admin_role_column_ready() {
    static $ready = null;
    if ($ready !== null) return $ready;
    $res = mysqli_query(db(), "SHOW COLUMNS FROM admin_users LIKE 'role'");
    $ready = (bool) ($res && mysqli_num_rows($res) > 0);
    return $ready;
}

/* True for the full 'admin' role, and also true (fail-open) if the role
   column doesn't exist yet, so nobody gets locked out mid-migration. */
function is_admin_role() {
    return !admin_role_column_ready() || ($_SESSION['admin_role'] ?? 'admin') === 'admin';
}

/* Gate a whole page to admin-role users only (e.g. Settings, Users). */
function require_admin_role() {
    if (!is_admin_role()) {
        flash("You don't have permission to view that page.");
        header('Location: index.php'); exit;
    }
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function check_csrf() {
    if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '_')) {
        die('Invalid session token. Please go back and try again.');
    }
}
function flash($msg = null) {
    if ($msg !== null) { $_SESSION['flash'] = $msg; return; }
    if (!empty($_SESSION['flash'])) { $m = $_SESSION['flash']; unset($_SESSION['flash']); return $m; }
    return null;
}
