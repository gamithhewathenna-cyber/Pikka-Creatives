<?php
$current_page = 'account'; $page_title = 'My Account';
require_once __DIR__ . '/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $cur = $_POST['current'] ?? '';
    $new = $_POST['new'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    $stmt = mysqli_prepare(db(), "SELECT password_hash FROM admin_users WHERE id=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['admin_id']);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$row || !password_verify($cur, $row['password_hash'])) {
        flash('Current password is incorrect.');
    } elseif (strlen($new) < 6) {
        flash('New password must be at least 6 characters.');
    } elseif ($new !== $confirm) {
        flash('New passwords do not match.');
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $up = mysqli_prepare(db(), "UPDATE admin_users SET password_hash=? WHERE id=?");
        mysqli_stmt_bind_param($up, 'si', $hash, $_SESSION['admin_id']);
        mysqli_stmt_execute($up);
        flash('Password changed successfully.');
    }
    header('Location: account.php'); exit;
}

require __DIR__ . '/header.php';
?>
<div class="card" style="max-width:480px">
  <h2>Change password</h2>
  <p class="sub">Logged in as <strong><?= e($_SESSION['admin_name']) ?></strong></p>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div class="field"><label>Current password</label><input type="password" name="current" required></div>
    <div class="field"><label>New password</label><input type="password" name="new" required></div>
    <div class="field"><label>Confirm new password</label><input type="password" name="confirm" required></div>
    <button class="btn" type="submit">Update password</button>
  </form>
</div>
<?php require __DIR__ . '/footer.php'; ?>
