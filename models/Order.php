<?php

namespace models;

use framework\ActiveRecord;

/**
 * Order Model.
 */
final class Order extends ActiveRecord
{
    public int $id;
    public string $reference;
    //public float $shipping_price;
    //public float $taxes;
    public float $total;
    public int $payment;
    //public string $paypal_order_id;
    //public string $paypal_transaction_id;
    public int $status;
    public int $created_at;
    public int $created_by;

    const STATUS_PENDING = 0;
    const STATUS_AWAITING = 1;
    const STATUS_DECLINED = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_SHIPPED = 4;
    const STATUS_DELIVERED = 5;
    const STATUS_PARTIALLY_REFUNDED = 6;
    const STATUS_REFUNDED = 7;

    const RETURN_TIME = 1296000; // 15 days

    const ACCEPTED_CURRENCY = 'EUR';
    const SPANISH_TAX = .10;

    const PAYMENT_BANK = 0;
    const PAYMENT_CREDIT_CARD = 1;
    const PAYMENT_PAYPAL = 2;


    public static function tableName(): string
    {
        return 'order';
    }

    public static function updateCond(): array
    {
        return [ 'order', [ 'id', 'created_by' ] ];
    }

    /**
     * Finds order by id
     *
     * @param int $id
     * @return ActiveRecord
     */
    public static function findById(int $id): ActiveRecord
    {
        return parent::findOne([
            'id' => $id
        ]);
    }

    /**
     * Finds order by reference
     *
     * @param string $reference
     * @return ActiveRecord
     */
    public static function findByReference(string $reference): ?ActiveRecord
    {
        return parent::findOne([
            'reference' => $reference
        ]);
    }

    /**
     * Get orders by created_by
     *
     * @param int $id
     * @return array|null
     */
    public function getUserOrders(int $id): ?array
    {
        return parent::findAll([
            'created_by' => $id
        ]);
    }
}
