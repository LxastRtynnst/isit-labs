<h1>Площадки</h1>

<div class="grid">
  <?php foreach ($areas as $area): ?>
    <div class="card">
      <h3><a href="/areas/<?= e($area['id']) ?>"><?= e($area['name']) ?></a></h3>
      <p><?= e($area['description']) ?></p>
    </div>
  <?php endforeach; ?>
</div>