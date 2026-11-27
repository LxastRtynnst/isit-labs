<?php
declare(strict_types=1);

namespace App\Models;

final class BookingRepository extends JsonRepository
{
    public function __construct()
    {
        $this->file = BASE_PATH . '/storage/bookings.json';
    }

    public function byArea(int $areaId): array
    {
        return array_values(array_filter(
            $this->all(),
            fn(array $booking): bool => $booking['area_id'] === $areaId
        ));
    }
}