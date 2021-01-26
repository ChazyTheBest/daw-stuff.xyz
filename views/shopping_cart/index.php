<?php

// TODO: implement translation system

/* @var $model \models\Cart */

$this->title = 'Carrito';

?>
<section>
    <h1>Contenido del carrito</h1>

    <table>
        <thead>
            <th>Nombre</th>
            <th>Unidades</th>
            <th>Precio</th>
            <th></th>
        </thead>
        <tbody>
<?php
$total = 0;
foreach ($model->getCartItems() as $item)
{
    $quantity = $_COOKIE['items'][$item['id']];
    $price = $item['price'] * $quantity;
    $total += $price;
    echo "<tr>
    <td>$item[name]</td>
    <td>
        <input type='hidden' id='unit-price-$item[id]' value='$item[price]'>
        <input class='update' type='number' min='1' data-url='/shoppingCart/update/$item[id]'
                                                    data-item-id='$item[id]'
                                                    data-old-value='$quantity' value='$quantity'>
    </td>
    <td>$price</td>
    <td><a class='delete' href='/shoppingCart/delete/$item[id]'>Delete</a></td>
</tr>";
}
?>
        <tr><td colspan="3"><br></td></tr>
        <tr><td colspan="2">Total:</td><td><?= $total ?></td></tr>
        </tbody>
        <tfoot>
            <td>Número total de unidades: <?= $model->getItemCount() ?></td>
        </tfoot>
    </table>

    <a href="/shop/index">Seguir comprando</a>
    <a id="pay" href="/order/create">Realizar compra</a>
</section>
