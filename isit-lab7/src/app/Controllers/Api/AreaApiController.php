<?php
// app/Controllers/Api/AreaApiController.php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\AreaRepository;
use App\Models\BookingRepository;

final class AreaApiController
{
    private AreaRepository $areas;
    private BookingRepository $bookings;

    public function __construct()
    {
        $this->areas = new AreaRepository();
        $this->bookings = new BookingRepository();
    }

    public function index(): void
    {
        json_response($this->areas->all());
    }

    public function show(string $id): void
    {
        $area = $this->areas->find((int)$id);
        if ($area === null) {
            json_error('Площадка не найдена', 404);
        }
        json_response($area);
    }

    public function tasks(string $id): void
    {
        $area = $this->areas->find((int)$id);
        if ($area === null) {
            json_error('Площадка не найдена', 404);
        }
        json_response($this->bookings->byArea((int)$id));
    }

    public function store(): void
    {
        $data = json_input();
        $errors = $this->validate($data);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }
        $area = $this->areas->add($this->clean($data));
        header('Location: /api/areas/' . $area['id']);
        json_response($area, 201);
    }

    public function update(string $id): void
    {
        $area = $this->areas->find((int)$id);
        if ($area === null) {
            json_error('Площадка не найдена', 404);
        }
        $data = json_input();
        $merged = array_merge($area, $this->clean($data));
        $errors = $this->validate($merged);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }
        $this->areas->update((int)$id, $merged);
        json_response($merged);
    }

    public function destroy(string $id): void
    {
        $area = $this->areas->find((int)$id);
        if ($area === null) {
            json_error('Площадка не найдена', 404);
        }
        // Проверка: если есть брони, удалять нельзя (409)
        $areaBookings = $this->bookings->byArea((int)$id);
        if (count($areaBookings) > 0) {
            json_error('Нельзя удалить площадку: у неё есть брони', 409);
        }
        $this->areas->delete((int)$id);
        no_content();
    }

    private function validate(array $data): array
    {
        $errors = [];
        $name = trim($data['name'] ?? '');
        if (mb_strlen($name) < 3) {
            $errors['name'] = 'Название — не короче 3 символов';
        }
        return $errors;
    }

    private function clean(array $data): array
    {
        return array_intersect_key($data, array_flip(['name', 'description']));
    }
}