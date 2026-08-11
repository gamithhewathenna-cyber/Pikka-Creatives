<?php
$current_page = 'users'; $page_title = 'User Accounts';
require_once __DIR__ . '/auth.php';
require_login();
require_admin_role();

$roleColumnReady = admin_role_column_ready();

/* ---------------- POST handling ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    if (!$roleColumnReady) {
        flash('User roles need a one-time database update before this page can be used — see the note below for the SQL to run.');
        header('Location: users.php'); exit;
    }

    $action = $_POST['action'] ?? '';
    $selfId = (int) $_SESSION['admin_id'];

    if ($action === 'add') {
        $username     = trim($_POST['username'] ?? '');
        $displayName  = trim($_POST['display_name'] ?? '');
        $password     = $_POST['password'] ?? '';
        $role         = ($_POST['role'] ?? '') === 'admin' ? 'admin' : 'editor';

        if ($username === '' || strlen($password) < 6) {
            flash('Username is required and password must be at least 6 characters.');
        } else {
            $st = mysqli_prepare(db(), "SELECT id FROM admin_users WHERE username=?");
            mysqli_stmt_bind_param($st, 's', $username);
            mysqli_stmt_execute($st);
            if (mysqli_fetch_assoc(mysqli_stmt_get_result($st))) {
                flash('That username is already taken.');
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare(db(), "INSERT INTO admin_users (username, password_hash, display_name, role) VALUES (?,?,?,?)");
                mysqli_stmt_bind_param($stmt, 'ssss', $username, $hash, $displayName, $role);
                mysqli_stmt_execute($stmt);
                flash('User added.');
            }
        }
    }
    elseif ($action === 'edit') {
        $id          = (int) ($_POST['id'] ?? 0);
        $displayName = trim($_POST['display_name'] ?? '');
        $password    = $_POST['password'] ?? '';
        $role        = ($_POST['role'] ?? '') === 'admin' ? 'admin' : 'editor';
        $isSelf      = $id === $selfId;

        // Never let someone change their own role — avoids accidental self-lockout.
        if ($isSelf) $role = 'admin';

        // Never allow the last remaining Admin to be demoted.
        if ($role === 'editor') {
            $remaining = mysqli_fetch_assoc(mysqli_query(db(), "SELECT COUNT(*) n FROM admin_users WHERE role='admin' AND id<>$id"))['n'];
            if ((int) $remaining < 1) {
                flash('You need at least one Admin account — make someone else Admin first.');
                header('Location: users.php'); exit;
            }
        }

        if ($password !== '') {
            if (strlen($password) < 6) {
                flash('New password must be at least 6 characters.');
                header('Location: users.php'); exit;
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare(db(), "UPDATE admin_users SET display_name=?, role=?, password_hash=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'sssi', $displayName, $role, $hash, $id);
        } else {
            $stmt = mysqli_prepare(db(), "UPDATE admin_users SET display_name=?, role=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, 'ssi', $displayName, $role, $id);
        }
        mysqli_stmt_execute($stmt);
        if ($isSelf && $displayName !== '') $_SESSION['admin_name'] = $displayName;
        flash('User updated.');
    }
    elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id === $selfId) {
            flash("You can't delete your own account while logged in as it.");
        } else {
            $row = mysqli_fetch_assoc(mysqli_query(db(), "SELECT role FROM admin_users WHERE id=$id"));
            $blocked = false;
            if ($row && $row['role'] === 'admin') {
                $remaining = mysqli_fetch_assoc(mysqli_query(db(), "SELECT COUNT(*) n FROM admin_users WHERE role='admin' AND id<>$id"))['n'];
                if ((int) $remaining < 1) {
                    flash('You need at least one Admin account — make someone else Admin first.');
                    $blocked = true;
                }
            }
            if (!$blocked) {
                $stmt = mysqli_prepare(db(), "DELETE FROM admin_users WHERE id=?");
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
                flash('User removed.');
            }
        }
    }

    header('Location: users.php'); exit;
}

$users = [];
if ($roleColumnReady) {
    $r = mysqli_query(db(), "SELECT * FROM admin_users ORDER BY id ASC");
    while ($x = mysqli_fetch_assoc($r)) $users[] = $x;
}

require __DIR__ . '/header.php';
?>
<?php if (!$roleColumnReady): ?>
  <div class="card">
    <h2>One-time setup needed</h2>
    <p class="sub">User roles need a small database update on this site. Open <strong>phpMyAdmin</strong> (in cPanel), select your database, go to the <strong>SQL</strong> tab, paste the code below, and click Go. This only needs to be done once — nothing else on the site is affected.</p>
    <textarea readonly rows="4" style="font-family:monospace;font-size:12.5px;white-space:pre" onclick="this.select()">ALTER TABLE `admin_users` ADD COLUMN `role` VARCHAR(20) NOT NULL DEFAULT 'admin' AFTER `display_name`;
UPDATE `admin_users` SET `role`='admin';</textarea>
    <p class="muted" style="margin-top:10px">Once that's run, refresh this page and the user editor will appear here.</p>
  </div>
<?php else: ?>
  <div class="card">
    <h2>User accounts</h2>
    <p class="sub"><strong>Admin</strong> has full access, including this page and Settings. <strong>Editor</strong> can manage all site content (Home, About, Contact, Our Work, Messages) but not user accounts or settings.</p>
  </div>
  <?php foreach ($users as $u): $isSelf = (int) $u['id'] === (int) $_SESSION['admin_id']; ?>
  <div class="card">
    <div class="rh" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
      <span class="pill"><?= e($u['username']) ?><?= $isSelf ? ' (you)' : '' ?></span>
      <?php if (!$isSelf): ?>
      <form method="post" onsubmit="return confirm('Remove this user?')">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?= $u['id'] ?>">
        <button class="btn btn-danger btn-sm">Delete</button>
      </form>
      <?php endif; ?>
    </div>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" value="<?= $u['id'] ?>">
      <div class="two">
        <div class="field"><label>Display name</label><input type="text" name="display_name" value="<?= e($u['display_name']) ?>"></div>
        <div class="field">
          <label>Role</label>
          <?php if ($isSelf): ?>
            <input type="text" value="Admin" disabled>
            <p class="muted" style="margin-top:6px">You can't change your own role.</p>
          <?php else: ?>
            <select name="role">
              <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin — full access</option>
              <option value="editor" <?= $u['role'] === 'editor' ? 'selected' : '' ?>>Editor — content only</option>
            </select>
          <?php endif; ?>
        </div>
      </div>
      <div class="field"><label>Reset password (leave blank to keep current)</label><input type="password" name="password" placeholder="New password" autocomplete="new-password"></div>
      <button class="btn btn-sm" type="submit">Save changes</button>
    </form>
  </div>
  <?php endforeach; ?>
  <div class="card" style="border-style:dashed">
    <h2>Add user</h2>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="add">
      <div class="two">
        <div class="field"><label>Username</label><input type="text" name="username" autocomplete="off"></div>
        <div class="field"><label>Display name</label><input type="text" name="display_name"></div>
      </div>
      <div class="two">
        <div class="field"><label>Password</label><input type="password" name="password" autocomplete="new-password"></div>
        <div class="field">
          <label>Role</label>
          <select name="role">
            <option value="editor" selected>Editor — content only</option>
            <option value="admin">Admin — full access</option>
          </select>
        </div>
      </div>
      <button class="btn" type="submit">+ Add user</button>
    </form>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
