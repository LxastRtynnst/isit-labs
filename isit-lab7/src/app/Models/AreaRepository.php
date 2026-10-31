<?php
declare(strict_types=1);

namespace App\Models;

final class AreaRepository extends JsonRepository
{
    public function __construct()
    {
        $this->file = BASE_PATH . '/storage/areas.json';
    }
}