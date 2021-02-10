<?php

namespace models;

use framework\App;

class OrderForm extends Model
{
    public int $payment;

    public function rules(): array
    {
        return [
            [ 'payment', 'required' ],
            [ 'payment', 'int', 'matches' => [ Order::PAYMENT_BANK,
                                               Order::PAYMENT_CREDIT_CARD,
                                               Order::PAYMENT_PAYPAL ] ]
        ];
    }

    public function attributeLabels(): array
    {
        return [ 'payment' => App::t('form', 'l_payment') ];
    }

    public function generateReference(): string
    {
        $seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $rand = '';

        foreach (array_rand($seed, 9) as $k)
        {
            $rand .= $seed[$k];
        }

        return $rand;
    }
}
