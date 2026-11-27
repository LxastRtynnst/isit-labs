<?php
declare(strict_types=1);

namespace App\Models;

abstract class JsonRepository
{
    protected string $file;

    public function all(): array
    {
        if (!is_file($this->file)) return [];
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    public function find(int $id): ?array
    {
        foreach ($this->all() as $item) {
            if ($item['id'] === $id) return $item;
        }
        return null;
    }

    public function add(array $data): array
    {
        $items = $this->all();
        $ids = array_column($items, 'id');
        $item = ['id' => ($ids ? max($ids) : 0) + 1] + $data;
        $items[] = $item;
        $this->save($items);
        return $item;
    }

    public function update(int $id, array $data): ?array
    {
        $items = $this->all();
        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                $item = array_merge($item, $data);
                $this->save($items);
                return $item;
            }
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $items = $this->all();
        $newItems = array_values(array_filter($items, fn($item) => $item['id'] !== $id));
        if (count($newItems) === count($items)) return false;
        $this->save($newItems);
        return true;
    }

    private function save(array $items): void
    {
        file_put_contents(
            $this->file,
            json_encode($items, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            LOCK_EX
        );
    }
}