<h1>Новая бронь</h1>

<form action="/bookings" method="post" class="card form">
  <label for="title">Название</label>
  <input id="title" type="text" name="title" value="<?= e($old['title'] ?? '') ?>">
  <?php if (isset($errors['title'])): ?><div class="error"><?= e($errors['title']) ?></div><?php endif; ?>

  <label for="area_id">Площадка</label>
  <select id="area_id" name="area_id">
    <option value="">Выберите площадку…</option>
    <?php foreach ($areas as $area): ?>
      <option value="<?= e($area['id']) ?>" <?= ((int) ($old['area_id'] ?? 0)) === $area['id'] ? 'selected' : '' ?>>
        <?= e($area['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <?php if (isset($errors['area_id'])): ?><div class="error"><?= e($errors['area_id']) ?></div><?php endif; ?>

  <label for="priority">Приоритет</label>
  <select id="priority" name="priority">
    <?php foreach (['low' => 'Низкий', 'medium' => 'Средний', 'high' => 'Высокий'] as $code => $name): ?>
      <option value="<?= e($code) ?>" <?= ($old['priority'] ?? 'medium') === $code ? 'selected' : '' ?>><?= e($name) ?></option>
    <?php endforeach; ?>
  </select>
  <?php if (isset($errors['priority'])): ?><div class="error"><?= e($errors['priority']) ?></div><?php endif; ?>

  <label for="due_date">Дата</label>
  <input id="due_date" type="date" name="due_date" value="<?= e($old['due_date'] ?? '') ?>">

  <p><button type="submit" class="btn">Создать бронь</button> <a href="/bookings">Отмена</a></p>
</form>
