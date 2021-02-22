<?php

/* @var $model Order */

use framework\App;
use models\Order;

$this->title = 'Order';

$statusList = Order::getStatusList();

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <table>
            <thead>
            <tr>
                <th>Reference</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
<?php foreach ($model->getUserOrders(App::$user->id) as $order): ?>
                <tr>
                    <td><?= $order['reference'] ?></td>
                    <td><?= $order['total'] ?> &euro;</td>
                    <td><?= $statusList[$order['status']] ?></td>
                    <td><?= date('d/m/Y', $order['created_at']) ?></td>
                    <td><a href="/order/view/<?= $order['id'] ?>">Details</a></td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>

    </section>
