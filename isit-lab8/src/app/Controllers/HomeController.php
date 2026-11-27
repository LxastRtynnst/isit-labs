<?php
// app/Controllers/HomeController.php — контроллер главной страницы.

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AreaRepository;
use App\Models\BookingRepository;

final class HomeController
{
    public function index(): void
    {
        $bookings = (new BookingRepository())->all();
        $areas = (new AreaRepository())->all();

        $byStatus = ['new' => 0, 'confirmed' => 0, 'cancelled' => 0];
        foreach ($bookings as $b) {
            $byStatus[$b['status']] = ($byStatus[$b['status']] ?? 0) + 1;
        }

        view('home', [
            'title'      => 'Главная',
            'total'      => count($bookings),
            'byStatus'   => $byStatus,
            'areasCount' => count($areas),
        ]);
    }
}