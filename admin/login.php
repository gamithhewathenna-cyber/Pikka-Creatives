<?php
require_once __DIR__ . '/auth.php';
if (!empty($_SESSION['admin_id'])) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $hasRole = admin_role_column_ready();
    $cols = $hasRole ? 'id, password_hash, display_name, role' : 'id, password_hash, display_name';
    $stmt = mysqli_prepare(db(), "SELECT $cols FROM admin_users WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $u);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;
    if ($row && password_verify($p, $row['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_name'] = $row['display_name'] ?: $u;
        $_SESSION['admin_role'] = $hasRole ? ($row['role'] ?: 'admin') : 'admin';
        header('Location: index.php'); exit;
    } else {
        $error = 'Incorrect username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login — Pikka Creatives</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head><body>
<div class="login-page">
  <form class="login-box" method="post">
    <div class="logo"><span class="dot">✳</span>Pikka<span style="color:var(--accent)">.</span></div>
    <p class="sub">Home Page content manager</p>
    <?php if ($error): ?><div class="err"><?= e($error) ?></div><?php endif; ?>
    <div class="field"><label>Username</label><input type="text" name="username" autofocus required></div>
    <div class="field"><label>Password</label><input type="password" name="password" required></div>
    <button class="btn" type="submit">Sign in →</button>
    <p class="hint">Default: <strong>admin</strong> / <strong>pikka123</strong> — change it after first login.</p>
  </form>
</div>
</body></html>
