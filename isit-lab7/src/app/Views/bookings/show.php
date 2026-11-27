<?php
$statusNames = ['new' => 'Новая', 'confirmed' => 'Подтверждена', 'cancelled' => 'Отменена'];
$priorityNames = ['low' => 'Низкий', 'medium' => 'Средний', 'high' => 'Высокий'];
?>
<p><a href="/bookings">← К списку броней</a></p>

<div class="card">
  <h1><?= e($booking['title']) ?></h1>
  <dl class="meta">
    <dt>Номер</dt>      <dd><?= e($booking['id']) ?></dd>
    <dt>Площадка</dt>   <dd><a href="/areas/<?= e($area['id']) ?>"><?= e($area['name']) ?></a></dd>
    <dt>Статус</dt>     <dd><span class="badge badge-<?= e($booking['status']) ?>"><?= e($statusNames[$booking['status']] ?? $booking['status']) ?></span></dd>
    <dt>Приоритет</dt>  <dd><?= e($priorityNames[$booking['priority']] ?? $booking['priority']) ?></dd>
    <dt>Дата</dt>       <dd><?= e($booking['due_date'] ?? '—') ?></dd>
  </dl>
</div>