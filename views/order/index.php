<?php

// TODO: implement translation system

/* @var $model Order */

use framework\App;
use models\Order;

$this->title = 'Order';

$statusList = [
    Order::STATUS_AWAITING => 'Awaiting payment',
    Order::STATUS_DECLINED => 'Declined'
];

?>
<section>
    <h1><?= $this->title ?></h1>

    <table>
        <thead>
            <th>Reference</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Options</th>
        </thead>
        <tbody>
<?php
foreach ($model->getUserOrders(App::$user->id) as $order)
{
    $status = $statusList[$order['status']];
    $date = date('d/m/Y', $order['created_at']);
    echo "<tr>
    <td>$order[reference]</td>
    <td>$order[total]</td>
    <td>$status</td>
    <td>$date</td>
    <td><a href='/order/view/$order[id]'>Details</a></td>
</tr>";
}
?>
        </tbody>
    </table>
</section>
