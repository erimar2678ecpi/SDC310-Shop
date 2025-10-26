<?php
namespace App\Models;

final class Cart
{
    private const SESSION_KEY = 'cart';

    /** @var array<int,int> */
    private array $items;

    public function __construct()
    {
        $this->items = $_SESSION[self::SESSION_KEY] ?? [];
    }

    /**
     * @return array<int,int>
     */
    public function all(): array
    {
        return $this->items;
    }

    public function quantity(int $productId): int
    {
        return $this->items[$productId] ?? 0;
    }

    public function increment(int $productId, int $maxAvailable): bool
    {
        $current = $this->quantity($productId);
        if ($current >= $maxAvailable) {
            return false;
        }

        $this->items[$productId] = $current + 1;
        $this->commit();

        return true;
    }

    public function decrement(int $productId): bool
    {
        if (!isset($this->items[$productId])) {
            return false;
        }

        $current = $this->items[$productId] - 1;
        if ($current <= 0) {
            unset($this->items[$productId]);
        } else {
            $this->items[$productId] = $current;
        }

        $this->commit();

        return true;
    }

    public function update(int $productId, int $quantity, int $maxAvailable): bool
    {
        $quantity = max(0, min($quantity, $maxAvailable));

        if ($quantity === 0) {
            $changed = isset($this->items[$productId]);
            unset($this->items[$productId]);
            if ($changed) {
                $this->commit();
            }
            return $changed;
        }

        $changed = !isset($this->items[$productId]) || $this->items[$productId] !== $quantity;
        $this->items[$productId] = $quantity;
        if ($changed) {
            $this->commit();
        }

        return $changed;
    }

    public function remove(int $productId): bool
    {
        if (!isset($this->items[$productId])) {
            return false;
        }

        unset($this->items[$productId]);
        $this->commit();

        return true;
    }

    public function reset(): void
    {
        $this->items = [];
        $this->commit();
    }

    private function commit(): void
    {
        if (empty($this->items)) {
            unset($_SESSION[self::SESSION_KEY]);
            return;
        }

        $_SESSION[self::SESSION_KEY] = $this->items;
    }
}
