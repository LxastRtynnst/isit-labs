<h1>Главная</h1>

<div class="stats">
  <div class="card">
    <div class="stat-value"><?= e($total) ?></div>
    <div class="stat-label">всего броней</div>
  </div>
  <div class="card">
    <div class="stat-value"><?= e($byStatus['new']) ?></div>
    <div class="stat-label">новых</div>
  </div>
  <div class="card">
    <div class="stat-value"><?= e($byStatus['confirmed']) ?></div>
    <div class="stat-label">подтверждено</div>
  </div>
  <div class="card">
    <div class="stat-value"><?= e($byStatus['cancelled']) ?></div>
    <div class="stat-label">отменено</div>
  </div>
</div>

<p>В системе <strong><?= e($areasCount) ?></strong> площадок. Перейдите к <a href="/bookings">списку броней</a> или <a href="/areas">площадкам</a>.</p>