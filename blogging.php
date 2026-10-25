<?php
require __DIR__ . '/db.php';
$q = trim($_GET['q'] ?? '');
$perPage = 5;
$page = max(1, (int)($_GET['page'] ?? 1));

$where = $q !== '' ? 'WHERE title LIKE :q OR body LIKE :q' : '';
$count = db()->prepare("SELECT COUNT(*) FROM posts $where");
if ($q !== '') $count->bindValue(':q', "%$q%");
$count->execute();
$total = (int)$count->fetchColumn();
$pages = max(1, (int)ceil($total / $perPage));
$page = min($page, $pages);

$stmt = db()->prepare("SELECT * FROM posts $where ORDER BY id DESC LIMIT :l OFFSET :o");
if ($q !== '') $stmt->bindValue(':q', "%$q%");
$stmt->bindValue(':l', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':o', ($page - 1) * $perPage, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

page_head('Simple Blog'); ?>
<form class="card row" method="get">
  <input name="q" value="<?= e($q) ?>" placeholder="Search posts" aria-label="Search posts">
  <button>Search</button>
</form>

<?php if (!$posts): ?>
  <div class="card"><p class="muted"><?= $q !== '' ? 'No posts match your search.' : 'No posts yet.' ?> <a href="edit.php">Write the first post</a>.</p></div>
<?php endif; ?>

<?php foreach ($posts as $p): ?>
  <article class="card">
    <h2><a href="post.php?id=<?= (int)$p['id'] ?>" style="color:inherit"><?= e($p['title']) ?></a></h2>
    <p class="muted">By <?= e($p['author']) ?> on <?= e(substr($p['created_at'], 0, 10)) ?></p>
    <p><?= e(mb_strimwidth($p['body'], 0, 220, '…')) ?></p>
    <a href="post.php?id=<?= (int)$p['id'] ?>">Read post</a>
  </article>
<?php endforeach; ?>

<?php if ($pages > 1): ?>
<p class="row">
  <?php if ($page > 1): ?><a class="btn ghost" href="?q=<?= urlencode($q) ?>&page=<?= $page - 1 ?>">Newer</a><?php endif; ?>
  <span class="muted">Page <?= $page ?> of <?= $pages ?></span>
  <?php if ($page < $pages): ?><a class="btn ghost" href="?q=<?= urlencode($q) ?>&page=<?= $page + 1 ?>">Older</a><?php endif; ?>
</p>
<?php endif; ?>
<?php page_foot();
