<?php
require __DIR__ . '/db.php';
$pdo = db();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        if ($title === '' || mb_strlen($title) > 200) {
            $error = 'Enter a task between 1 and 200 characters.';
        } else {
            $pdo->prepare('INSERT INTO tasks (title) VALUES (?)')->execute([$title]);
        }
    } elseif ($action === 'toggle') {
        $pdo->prepare('UPDATE tasks SET is_done = 1 - is_done WHERE id = ?')->execute([$id]);
    } elseif ($action === 'delete') {
        $pdo->prepare('DELETE FROM tasks WHERE id = ?')->execute([$id]);
    } elseif ($action === 'clear_done') {
        $pdo->exec('DELETE FROM tasks WHERE is_done = 1');
    }
    if ($error === '') { header('Location: index.php'); exit; } // Post/Redirect/Get
}

$tasks = $pdo->query('SELECT * FROM tasks ORDER BY is_done, id DESC')->fetchAll();
$open = count(array_filter($tasks, fn($t) => !$t['is_done']));
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>To-Do List</title><link rel="stylesheet" href="style.css">
</head><body>
<header><h1>To-Do List</h1><span class="muted" style="color:#cfe0f7"><?= $open ?> open</span></header>
<main>
  <?php if ($error): ?><div class="err"><?= e($error) ?></div><?php endif; ?>
  <form class="card row" method="post">
    <input type="hidden" name="action" value="add">
    <input name="title" placeholder="What needs doing?" maxlength="200" required autofocus aria-label="New task">
    <button>Add task</button>
  </form>

  <div class="card">
    <?php if (!$tasks): ?>
      <p class="muted">No tasks yet. Add your first one above.</p>
    <?php else: ?>
    <ul class="items">
      <?php foreach ($tasks as $t): ?>
      <li class="<?= $t['is_done'] ? 'done' : '' ?>">
        <form class="inline" method="post">
          <input type="hidden" name="action" value="toggle">
          <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
          <button class="ghost"><?= $t['is_done'] ? 'Reopen' : 'Done' ?></button>
        </form>
        <span class="grow t"><?= e($t['title']) ?></span>
        <form class="inline" method="post" onsubmit="return confirm('Delete this task?')">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
          <button class="danger">Delete</button>
        </form>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>

  <?php if (count($tasks) > $open): ?>
  <form method="post"><input type="hidden" name="action" value="clear_done"><button class="ghost">Clear completed</button></form>
  <?php endif; ?>
</main>
</body></html>
