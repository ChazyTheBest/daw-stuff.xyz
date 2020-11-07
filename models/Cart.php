<?php

namespace models;

/**
 * Cart Model.
 */
final class Cart extends Model
{
    private array $dataSource;

    public function __construct()
    {
        parent::__construct($this);

        $this->dataSource =
        [
            'ref1' => [ 'desc' => 'Descripción Artículo 1', 'price' => 5 ],
            'ref2' => [ 'desc' => 'Descripción Artículo 2', 'price' => 3.3 ],
            'ref3' => [ 'desc' => 'Descripción Artículo 3', 'price' => 2 ],
        ];
    }

    // TODO: replace $_SESSION with a more appropriate storage
    // for example a mysql database
    public function addItem(string $ref): void
    {
        if (!isset($this->dataSource[$ref]))
            return;

        if (!isset($_COOKIE['items'][$ref]))
            setcookie("items[$ref]", 1, time() + 3600, '/');  /* expire in 1 hour */
    }

    public function getItems(): string
    {
        $items = '';

        foreach ($this->dataSource as $key => $val)
            $items .= "<tr>\n<td>$key</td>\n<td>$val[desc]</td>\n<td>$val[price]</td>\n<td><a class='buy' href='/shoppingCart/add/$key'>Comprar</a></td>\n</tr>\n";

        return $items;
    }

    public function getItemCount(): int
    {
        return isset($_COOKIE['items']) ? count($_COOKIE['items']) : 0;
    }

    // DRY!!!
    public function getCartItems(): string
    {
        $items = '';

        if (!isset($_COOKIE['items']))
            return '';

        foreach ($_COOKIE['items'] as $key => $val)
            $items .= "<tr>\n<td>$key</td>\n<td>$val</td>\n</tr>\n";

        return $items;
    }

    public function processCart(): void
    {
        if (isset($_COOKIE['items']))
        {
            foreach ($_COOKIE['items'] as $key => $val)
                setcookie("items[$key]", '', time() - 3600, '/');
        }
    }
}
