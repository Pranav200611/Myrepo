<?php
require __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    db()->prepare('DELETE FROM bookings WHERE id = ?')->execute([(int)($_POST['id'] ?? 0)]);
    flash('Booking cancelled.');
    header('Location: bookings.php'); exit;
}
$rows = db()->query("SELECT b.*, r.name AS room, r.price_per_night AS rate
    FROM bookings b JOIN rooms r ON r.id = b.room_id
    ORDER BY b.check_in DESC")->fetchAll();
page_head('All bookings'); ?>
<div class="card">
  <h2>All bookings</h2>
  <?php if (!$rows): ?>
    <p class="muted">No bookings yet. <a href="index.php">Book a room</a>.</p>
  <?php else: ?>
  <div style="overflow-x:auto"><table>
    <tr><th>Room</th><th>Guest</th><th>Dates</th><th>Total</th><th></th></tr>
    <?php foreach ($rows as $b):
      $n = (int)((strtotime($b['check_out']) - strtotime($b['check_in'])) / 86400); ?>
    <tr>
      <td><?= e($b['room']) ?></td>
      <td><?= e($b['guest_name']) ?><br><span class="muted"><?= e($b['guest_email']) ?></span></td>
      <td><?= e($b['check_in']) ?> to <?= e($b['check_out']) ?><br><span class="muted"><?= $n ?> night<?= $n === 1 ? '' : 's' ?></span></td>
      <td>₹<?= number_format($b['rate'] * $n) ?></td>
      <td>
        <form class="inline" method="post" onsubmit="return confirm('Cancel this booking?')">
          <input type="hidden" name="csrf" value="<?= csrf() ?>">
          <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
          <button class="danger">Cancel</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table></div>
  <?php endif; ?>
</div>
<?php page_foot();
