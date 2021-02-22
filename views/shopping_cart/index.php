<?php

/* @var $model BrowserCart|UserCart */

use models\BrowserCart;
use models\UserCart;

$this->title = 'Shopping Cart';

$total = 0;
$data = $model->getCartItems();
$items = $data['items'];

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Unidades</th>
                    <th>Precio</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($data['products'] as $product): $total += $product['price'] * $items[$product['id']]; ?>
                <tr>
                    <td><?= $product['name'] ?></td>
                    <td>
                        <input type="hidden" id="unit-price-<?= $product['id'] ?>" value="<?= $product['price'] ?>">
                        <input class="cart_update" type="number" min="1" max="999" data-url="/shoppingCart/update/<?= $product['id'] ?>"
                                                                              data-item-id="<?= $product['id'] ?>"
                                                                              data-old-value="<?= $items[$product['id']] ?>" value="<?= $items[$product['id']] ?>">
                    </td>
                    <td><?= $product['price'] * $items[$product['id']] ?> &euro;</td>
                    <td><a class="cart_delete" href="/shoppingCart/delete/<?= $product['id'] ?>">Delete</a></td>
                </tr>
<?php endforeach; ?>
                <tr><td colspan="3"><br></td></tr>
                <tr><td colspan="2">Total:</td><td><?= $total ?> &euro;</td></tr>
            </tbody>
            <tfoot>
                <tr><td>Número total de unidades: <?= $model->getItemCount() ?></td></tr>
            </tfoot>
        </table>

        <a href="/shop/index">Seguir comprando</a>
        <a id="pay" href="/order/create">Realizar compra</a>

    </section>
