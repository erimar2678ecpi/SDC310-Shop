<?php
namespace App\Models;

final class Cart
{
    private array $items = [];
    public function add(int $productId): void { $this->items[] = $productId; }
    public function remove(int $productId): void { $this->items = array_filter($this->items, fn($v) => $v !== $productId); }
    public function all(): array { return $this->items; }
}
