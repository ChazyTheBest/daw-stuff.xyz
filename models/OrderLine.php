<?php

namespace models;

use framework\ActiveRecord;

/**
 * OrderLine Model.
 */
class OrderLine extends ActiveRecord
{
    public int $product_id;
    public int $quantity;
    public float $price;
    public int $order_id;


    public static function tableName(): string
    {
        return 'order_line';
    }

    public static function updateCond(): array
    {
        return [ 'order_line', [ 'product_id', 'order_id' ] ];
    }

    /**
     * Get lines by order_id
     *
     * @param int $id
     * @return array|null
     */
    public function getByOrderId(int $id): ?array
    {
        return parent::findAll([
            'order_id' => $id
        ]);
    }
}
