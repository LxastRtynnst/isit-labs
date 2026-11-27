<?php
$statusNames = ['new' => 'Новая', 'confirmed' => 'Подтверждена', 'cancelled' => 'Отменена'];
$priorityNames = ['low' => 'Низкий', 'medium' => 'Средний', 'high' => 'Высокий'];
?>
<h1>Брони</h1>

<p class="filters">
  Показать:
  <a href="/bookings" class="<?= $status === '' ? 'active' : '' ?>">все</a>
  <?php foreach ($statusNames as $code => $name): ?>
    <a href="/bookings?status=<?= e($code) ?>" class="<?= $status === $code ? 'active' : '' ?>"><?= e($name) ?></a>
  <?php endforeach; ?>
</p>

<?php if (count($bookings) === 0): ?>
  <p class="empty">Брони не найдены.</p>
<?php else: ?>
  <table>
    <thead>
      <tr><th>№</th><th>Название</th><th>Статус</th><th>Приоритет</th><th>Дата</th></tr>
    </thead>
    <tbody>
      <?php foreach ($bookings as $booking): ?>
        <tr>
          <td><?= e($booking['id']) ?></td>
          <td><a href="/bookings/<?= e($booking['id']) ?>"><?= e($booking['title']) ?></a></td>
          <td><span class="badge badge-<?= e($booking['status']) ?>"><?= e($statusNames[$booking['status']] ?? $booking['status']) ?></span></td>
          <td><?= e($priorityNames[$booking['priority']] ?? $booking['priority']) ?></td>
          <td><?= e($booking['due_date'] ?? '—') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
