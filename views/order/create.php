<?php

/* @var $model OrderForm */

use models\OrderForm;

$this->title = 'Payment';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <article>
            <p>Select a payment option:</p>

            <form id="OrderForm" action="/order/create" method="POST">
                <label>
                    Wire/Bank Transfer
                    <input type="radio" name="OrderForm[payment]" value="0">
                </label>
                <label>
                    Credit Card: Visa, MasterCard
                    <input type="radio" name="OrderForm[payment]" value="1">
                </label>
                <label>
                    Paypal: Pay easy
                    <input type="radio" name="OrderForm[payment]" value="2">
                </label>

                <input type="submit" value="Pay">
            </form>
        </article>

    </section>
