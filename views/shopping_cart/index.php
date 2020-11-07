<?php

// TODO: implement page title
// TODO: implement translation system

/* @var $model \models\Cart */

$count = $model->getItemCount();

?>
<section>
    <h1>Contenido del carrito</h1>

    <p></p>

    <table>
        <thead>
            <th>Referencia</th>
            <th>Unidades</th>
        </thead>
        <tbody>
            <?= $model->getCartItems() ?>
        </tbody>
        <tfoot>
            <td>Número total de unidades: <?= $count ?></td>
        </tfoot>
    </table>

    <a href="/shop/index">Seguir comprando</a>
    <a id="pay" href="/shoppingCart/pay">Realizar compra</a>
</section>
