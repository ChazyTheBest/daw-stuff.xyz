<?php

// TODO: implement translation system

/* @var $model Order */
/* @var $lines OrderLine */

use models\Order;
use models\OrderLine;
use models\Product;

$this->title = 'Order Details';

$statusList = [
    Order::STATUS_AWAITING => 'Awaiting payment',
    Order::STATUS_DECLINED => 'Declined'
];

?>
<section>
    <h1><?= $this->title ?></h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Units</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
<?php
foreach ($lines->getByOrderId($model->id) as $item)
{
    $name = Product::findById($item['product_id'])->name;
    echo "<tr><td>$name</td><td>$item[quantity]</td><td>$item[price]</td></tr>";
}
?>
        <tr><td colspan="3"><br></td></tr>
        <tr><td colspan="2">Total:</td><td><?= $model->total ?></td></tr>
        </tbody>
    </table>
</section>
