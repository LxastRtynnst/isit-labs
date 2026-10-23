<?php
// app/Models/BookingRepository.php — модель броней.

declare(strict_types=1);

namespace App\Models;

final class BookingRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = BASE_PATH . '/storage/bookings.json';
    }

    public function all(): array
    {
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    public function find(int $id): ?array
    {
        foreach ($this->all() as $booking) {
            if ($booking['id'] === $id) {
                return $booking;
            }
        }
        return null;
    }

    public function byArea(int $areaId): array
    {
        return array_values(array_filter(
            $this->all(),
            fn(array $booking): bool => $booking['area_id'] === $areaId
        ));
    }

    public function add(array $data): array
    {
        $bookings = $this->all();
        $ids = array_column($bookings, 'id');
        $booking = ['id' => ($ids ? max($ids) : 0) + 1] + $data;
        $bookings[] = $booking;

        file_put_contents(
            $this->file,
            json_encode($bookings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            LOCK_EX
        );
        return $booking;
    }
}