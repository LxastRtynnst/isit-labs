<?php
// app/Controllers/AreaController.php — контроллер площадок.

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AreaRepository;
use App\Models\BookingRepository;

final class AreaController
{
    public function index(): void
    {
        view('areas/index', [
            'title' => 'Площадки',
            'areas' => (new AreaRepository())->all(),
        ]);
    }

    public function show(string $id): void
    {
        $area = (new AreaRepository())->find((int) $id);
        if ($area === null) {
            notFound();
        }

        view('areas/show', [
            'title'    => $area['name'],
            'area'     => $area,
            'bookings' => (new BookingRepository())->byArea($area['id']),
        ]);
    }
}