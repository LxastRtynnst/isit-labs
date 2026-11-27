<?php
$statusNames = ['new' => 'Новая', 'confirmed' => 'Подтверждена', 'cancelled' => 'Отменена'];
?>
<p><a href="/areas">← Ко всем площадкам</a></p>

<div class="card">
  <h1><?= e($area['name']) ?></h1>
  <p><?= e($area['description']) ?></p>
</div>

<h2>Брони этой площадки (<?= count($bookings) ?>)</h2>

<?php if (count($bookings) === 0): ?>
  <p class="empty">У площадки пока нет броней.</p>
<?php else: ?>
  <ul class="list">
    <?php foreach ($bookings as $booking): ?>
      <li>
        <a href="/bookings/<?= e($booking['id']) ?>"><?= e($booking['title']) ?></a>
        <span class="badge badge-<?= e($booking['status']) ?>"><?= e($statusNames[$booking['status']] ?? $booking['status']) ?></span>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>