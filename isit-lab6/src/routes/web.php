<?php

use App\Controllers\AreaController;
use App\Controllers\BookingController;
use App\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/bookings', [BookingController::class, 'index']);
$router->get('/bookings/create', [BookingController::class, 'create']);
$router->post('/bookings', [BookingController::class, 'store']);
$router->get('/bookings/{id}', [BookingController::class, 'show']);

$router->get('/areas', [AreaController::class, 'index']);
$router->get('/areas/{id}', [AreaController::class, 'show']);