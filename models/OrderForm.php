<?php

namespace models;

use framework\App;

class OrderForm extends FormModel
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
        if ($this->attributeLabels === [])
        {
            $this->attributeLabels = [ 'payment' => App::t('form', 'l_payment') ];
        }

        return $this->attributeLabels;
    }

    public function attributeLabel(string $key): string
    {
        return $this->attributeLabels()[$key];
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
