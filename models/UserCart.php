<?php

namespace models;

use framework\ActiveRecord;
use framework\App;
use PDO;

final class UserCart extends ActiveRecord
{
    public int $product_id;
    public int $quantity;
    public int $created_by;


    /**
     * @return string the table name.
     */
    public static function tableName(): string
    {
        return 'user_cart';
    }

    public static function updateCond(): array
    {
        return [ 'user_cart', [ 'product_id', 'created_by' ] ];
    }

    /**
     * Finds item by product_id
     *
     * @param int $id
     * @return ActiveRecord
     */
    public static function findById(int $id): ?ActiveRecord
    {
        return parent::findOne([
            'product_id' => $id,
            'created_by' => App::$user->id
        ]);
    }

    public function deleteItem(): void
    {
        $this->delete([
            'product_id' => $this->product_id,
            'created_by' => $this->created_by
        ]);
    }

    public static function getItemCount(): int
    {
        return self::sum('quantity', [ 'created_by' => App::$user->id ]);
    }

    public function getCartItems(): array
    {
        $ids = [];

        $items = $this->custom([
            'select' => [ 'product_id', 'quantity' ],
            'cond' => [ 'created_by' => App::$user->id ]
        ], PDO::FETCH_KEY_PAIR);

        if ($items === [])
            return [ 'items' => [], 'products' => [] ];

        foreach ($items as $key => $val)
            $ids[] = $key;

        return [
            'items' => $items,
            'products' => Product::findManyById($ids)
        ];
    }

    public function emptyCart(): void
    {
        $this->delete([ 'created_by' => App::$user->id ]);
    }
}
