<?php

namespace models;

use framework\ActiveRecord;
use framework\App;

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

    const STATUS_DELETED = 0;
    const STATUS_PENDING = 1;
    const STATUS_AWAITING = 2;
    const STATUS_DECLINED = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_SHIPPED = 5;
    const STATUS_DELIVERED = 6;
    const STATUS_PARTIALLY_REFUNDED = 7;
    const STATUS_REFUNDED = 8;

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
    public static function findById(int $id): ?ActiveRecord
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

    public static function getList(): array
    {
        return (new Order())->custom([
            'select' => [ 'id', 'reference', 'total', 'status', 'created_at', 'created_by' ],
            'order' => [ 'id' ]
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
            'created_by' => $id,
            'operator' => [ 'status', '<>', Order::STATUS_DELETED ]
        ]);
    }

    public function checkStatus(int $status): bool
    {
        return in_array($status, [
            Order::STATUS_PENDING,
            Order::STATUS_AWAITING,
            Order::STATUS_DECLINED,
            Order::STATUS_COMPLETED,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            Order::STATUS_PARTIALLY_REFUNDED,
            Order::STATUS_REFUNDED
        ]);
    }

    public function setStatus(int $status): void
    {
        $this->status($status);
    }

    public static function getStatusList(): array
    {
        return [
            Order::STATUS_DELETED => App::t('table', 'td_status_0'),
            Order::STATUS_PENDING => App::t('table', 'td_status_1'),
            Order::STATUS_AWAITING => App::t('table', 'td_status_2'),
            Order::STATUS_DECLINED => App::t('table', 'td_status_3'),
            Order::STATUS_COMPLETED => App::t('table', 'td_status_4'),
            Order::STATUS_SHIPPED => App::t('table', 'td_status_5'),
            Order::STATUS_DELIVERED => App::t('table', 'td_status_6'),
            Order::STATUS_PARTIALLY_REFUNDED => App::t('table', 'td_status_7'),
            Order::STATUS_REFUNDED => App::t('table', 'td_status_8')
        ];
    }
}
