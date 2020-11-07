<?php

// TODO: implement page title
// TODO: implement translation system
// TODO: pass the Shop model to display data instead of hardcoing it inside the Cart Model

/* @var $model \models\Cart */

$count = $model->getItemCount();

?>
<section>
    <h1>Mi Tienda</h1>

    <p><?= $count > 0 ? "Llevas <span id='count'>$count</span> articulos seleccionados." : 'El carrito está vacio.' ?></p>

    <table>
        <thead>
            <th>Referencia</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th></th>
        </thead>
        <tbody>
            <?= $model->getItems() ?>
        </tbody>
    </table>

    <a href="/shoppingCart/index">Ver el carrito</a>
</section>
