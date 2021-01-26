<?php

namespace models;

/**
 * OrderLine Model.
 */
class OrderLine extends \framework\ActiveRecord
{
    public int $id;
    public int $product_id;
    public int $quantity;
    public float $price;
    public int $order_id;


    public static function tableName(): string
    {
        return 'order_line';
    }

    /**
     * Get lines by order_id
     *
     * @param int $id
     * @return array|null
     */
    public function getOrderLines(int $id): ?array
    {
        return parent::findAll([
            'order_id' => $id
        ]);
    }
}
