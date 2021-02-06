<?php

namespace models;

use framework\App;

/**
 * Browser Cart Model.
 */
final class BrowserCart
{
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function addItem(int $id, int $quantity): void
    {
        $cookie = $this->items[$id] ?? false;
        if ($cookie && ($cookie + $quantity) > 0 && ($cookie + $quantity) < 1000)
            $quantity += $cookie;

        setcookie("items[$id]", $quantity, App::$config['cart']['cookie_expires'], '/');
    }

    public function updateItem(int $id, int $quantity): void
    {
        $cookie = $this->items[$id] ?? false;
        if ($cookie && $quantity > 0 && $quantity < 1000)
            setcookie("items[$id]", $quantity, App::$config['cart']['cookie_expires'], '/');
    }

    public function deleteItem(int $id): void
    {
        if (!isset($this->items[$id]))
            return;

        setcookie("items[$id]", '', time() - 3600, '/');
    }

    public function getItemCount(): int
    {
        return array_sum($this->items);
    }

    public function getCartItems(): array
    {
        $ids = [];

        if ($this->items === [])
            return [ 'items' => [], 'products' => [] ];

        foreach ($this->items as $key => $val)
            $ids[] = $key;

        return [
            'items' => $this->items,
            'products' => Product::findManyById($ids)
        ];
    }

    public function emptyCart(): void
    {
        foreach ($this->items as $key => $val)
            setcookie("items[$key]", '', time() - 3600, '/');
    }
}
