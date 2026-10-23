<?php
// app/Models/AreaRepository.php — модель площадок.

declare(strict_types=1);

namespace App\Models;

final class AreaRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = BASE_PATH . '/storage/areas.json';
    }

    public function all(): array
    {
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    public function find(int $id): ?array
    {
        foreach ($this->all() as $area) {
            if ($area['id'] === $id) {
                return $area;
            }
        }
        return null;
    }
}