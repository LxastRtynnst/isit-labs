<?php
// app/Controllers/Api/BookingApiController.php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\AreaRepository;
use App\Models\BookingRepository;

final class BookingApiController
{
    private BookingRepository $bookings;
    private AreaRepository $areas;

    public function __construct()
    {
        $this->bookings = new BookingRepository();
        $this->areas = new AreaRepository();
    }

    public function index(): void
    {
        $status = $_GET['status'] ?? '';
        $areaId = $_GET['area_id'] ?? '';
        $bookings = $this->bookings->all();

        if ($status !== '') {
            $bookings = array_values(array_filter($bookings, fn($b) => $b['status'] === $status));
        }
        if ($areaId !== '') {
            $bookings = array_values(array_filter($bookings, fn($b) => (string)$b['area_id'] === (string)$areaId));
        }

        json_response($bookings);
    }

    public function show(string $id): void
    {
        $booking = $this->bookings->find((int)$id);
        if ($booking === null) {
            json_error('Бронь не найдена', 404);
        }
        json_response($booking);
    }

    public function store(): void
    {
        $data = json_input();
        $errors = $this->validate($data);

        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }

        $booking = $this->bookings->add($this->clean($data));
        header('Location: /api/bookings/' . $booking['id']);
        json_response($booking, 201);
    }

    public function update(string $id): void
    {
        $booking = $this->bookings->find((int)$id);
        if ($booking === null) {
            json_error('Бронь не найдена', 404);
        }

        $data = json_input();
        $merged = array_merge($booking, $this->clean($data));
        $errors = $this->validate($merged);

        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }

        $this->bookings->update((int)$id, $merged);
        json_response($merged);
    }

    public function destroy(string $id): void
    {
        $booking = $this->bookings->find((int)$id);
        if ($booking === null) {
            json_error('Бронь не найдена', 404);
        }

        $this->bookings->delete((int)$id);
        no_content();
    }

    private function validate(array $data): array
    {
        $errors = [];
        $title = trim($data['title'] ?? '');
        if (mb_strlen($title) < 3) {
            $errors['title'] = 'Название — не короче 3 символов';
        }
        $areaId = $data['area_id'] ?? 0;
        if ($this->areas->find((int)$areaId) === null) {
            $errors['area_id'] = 'Площадки с таким номером нет';
        }
        $status = $data['status'] ?? 'new';
        if (!in_array($status, ['new', 'confirmed', 'cancelled'], true)) {
            $errors['status'] = 'Статус: new, confirmed или cancelled';
        }
        $priority = $data['priority'] ?? 'medium';
        if (!in_array($priority, ['low', 'medium', 'high'], true)) {
            $errors['priority'] = 'Приоритет: low, medium или high';
        }
        return $errors;
    }

    private function clean(array $data): array
    {
        return array_intersect_key($data, array_flip(['area_id', 'title', 'status', 'priority', 'due_date']));
    }
}