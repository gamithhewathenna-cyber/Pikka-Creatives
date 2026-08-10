<?php
/* Generic list CRUD used by services/why/process/stats pages.
   Define before include: $table, $columns (assoc key=>label,type),
   $current_page, $page_title, $intro. */
require_once __DIR__ . '/auth.php';
require_login();

$allowed = ['services','why_reasons','process_steps','stats'];
if (!in_array($table, $allowed, true)) die('bad table');

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $cols = array_keys($columns);
        $vals = [];
        foreach ($cols as $col) $vals[$col] = trim($_POST[$col] ?? '');

        if ($action === 'add') {
            // next sort order
            $ord = mysqli_fetch_assoc(mysqli_query(db(), "SELECT COALESCE(MAX(sort_order),0)+1 n FROM `$table`"))['n'];
            $colList = implode(',', array_map(fn($c)=>"`$c`", $cols)) . ',sort_order';
            $ph = implode(',', array_fill(0, count($cols)+1, '?'));
            $types = str_repeat('s', count($cols)) . 'i';
            $stmt = mysqli_prepare(db(), "INSERT INTO `$table` ($colList) VALUES ($ph)");
            $bind = array_values($vals); $bind[] = $ord;
            mysqli_stmt_bind_param($stmt, $types, ...$bind);
            mysqli_stmt_execute($stmt);
            flash('Item added.');
        } else {
            $id = (int)($_POST['id'] ?? 0);
            $set = implode(',', array_map(fn($c)=>"`$c`=?", $cols));
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
        for ($i=0;$i<count($rows);$i++){
            if ((int)$rows[$i]['id'] === $id){
                $j = $dir==='up' ? $i-1 : $i+1;
                if ($j>=0 && $j<count($rows)){
                    $a=$rows[$i]['id']; $b=$rows[$j]['id'];
                    $oa=$rows[$i]['sort_order']; $ob=$rows[$j]['sort_order'];
                    mysqli_query(db(), "UPDATE `$table` SET sort_order=$ob WHERE id=$a");
                    mysqli_query(db(), "UPDATE `$table` SET sort_order=$oa WHERE id=$b");
                }
                break;
            }
        }
    }
    header("Location: $current_page.php"); exit;
}

$rows = [];
$r = mysqli_query(db(), "SELECT * FROM `$table` ORDER BY sort_order ASC, id ASC");
while ($x = mysqli_fetch_assoc($r)) $rows[] = $x;

require __DIR__ . '/header.php';

function render_fields($columns, $data = []) {
    foreach ($columns as $col => $meta) {
        $label = $meta['label']; $type = $meta['type'] ?? 'text';
        $val = $data[$col] ?? '';
        echo '<div class="field"><label>'.e($label).'</label>';
        if ($type === 'textarea') {
            echo '<textarea name="'.e($col).'" rows="3">'.e($val).'</textarea>';
        } else {
            echo '<input type="text" name="'.e($col).'" value="'.e($val).'">';
        }
        echo '</div>';
    }
}
?>
<div class="card">
  <h2><?= e($page_title) ?></h2>
  <p class="sub"><?= e($intro ?? '') ?></p>
</div>

<?php foreach ($rows as $idx => $row): ?>
<div class="card">
  <div class="rh" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
    <span class="pill">#<?= $idx+1 ?></span>
    <div style="display:flex;gap:6px">
      <form method="post" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="dir" value="up"><button class="btn btn-ghost btn-sm" <?= $idx===0?'disabled':'' ?>>↑</button></form>
      <form method="post" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="move"><input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="dir" value="down"><button class="btn btn-ghost btn-sm" <?= $idx===count($rows)-1?'disabled':'' ?>>↓</button></form>
      <form method="post" style="display:inline" onsubmit="return confirm('Delete this item?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $row['id'] ?>"><button class="btn btn-danger btn-sm">Delete</button></form>
    </div>
  </div>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <?php render_fields($columns, $row); ?>
    <button class="btn btn-sm" type="submit">Save changes</button>
  </form>
</div>
<?php endforeach; ?>

<div class="card" style="border-style:dashed">
  <h2>Add new</h2>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="action" value="add">
    <?php render_fields($columns); ?>
    <button class="btn" type="submit">+ Add item</button>
  </form>
</div>
<?php require __DIR__ . '/footer.php'; ?>
