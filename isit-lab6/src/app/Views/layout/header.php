<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Страница') ?> — <?= e(config('name')) ?></title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <header class="topbar">
    <a class="brand" href="/"><?= e(config('name')) ?></a>
    <nav>
      <a href="/">Главная</a>
      <a href="/bookings">Брони</a>
      <a href="/areas">Площадки</a>
      <a href="/bookings/create" class="btn-small">+ Новая бронь</a>
    </nav>
  </header>
  <main class="container">
