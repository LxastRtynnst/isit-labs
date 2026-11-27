<?php

use App\Controllers\HomeController;
use App\Controllers\BookingController;
use App\Controllers\AreaController;

use App\Controllers\Api\BookingApiController;
use App\Controllers\Api\AreaApiController;
use App\Controllers\Api\UserApiController;

// --- САЙТ (из лабы 6) ---
$router->get('/', [HomeController::class, 'index']);
$router->get('/bookings', [BookingController::class, 'index']);
$router->get('/bookings/create', [BookingController::class, 'create']);
$router->post('/bookings', [BookingController::class, 'store']);
$router->get('/bookings/{id}', [BookingController::class, 'show']);
$router->get('/areas', [AreaController::class, 'index']);
$router->get('/areas/{id}', [AreaController::class, 'show']);

// --- REST API ---
// Брони
$router->get('/api/bookings', [BookingApiController::class, 'index']);
$router->get('/api/bookings/{id}', [BookingApiController::class, 'show']);
$router->post('/api/bookings', [BookingApiController::class, 'store']);
$router->put('/api/bookings/{id}', [BookingApiController::class, 'update']);
$router->patch('/api/bookings/{id}', [BookingApiController::class, 'update']);
$router->delete('/api/bookings/{id}', [BookingApiController::class, 'destroy']);

// Площадки
$router->get('/api/areas', [AreaApiController::class, 'index']);
$router->get('/api/areas/{id}', [AreaApiController::class, 'show']);
$router->get('/api/areas/{id}/bookings', [AreaApiController::class, 'tasks']);
$router->post('/api/areas', [AreaApiController::class, 'store']);
$router->put('/api/areas/{id}', [AreaApiController::class, 'update']);
$router->patch('/api/areas/{id}', [AreaApiController::class, 'update']);
$router->delete('/api/areas/{id}', [AreaApiController::class, 'destroy']);

// Пользователи
$router->get('/api/users', [UserApiController::class, 'index']);
$router->get('/api/users/{id}', [UserApiController::class, 'show']);
$router->post('/api/users', [UserApiController::class, 'store']);
$router->put('/api/users/{id}', [UserApiController::class, 'update']);
$router->patch('/api/users/{id}', [UserApiController::class, 'update']);
$router->delete('/api/users/{id}', [UserApiController::class, 'destroy']);