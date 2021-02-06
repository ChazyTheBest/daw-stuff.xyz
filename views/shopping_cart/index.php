<?php

// TODO: implement translation system

/* @var $model \models\BrowserCart|\models\UserCart */

$this->title = 'Carrito';

?>
<section>
    <h1>Contenido del carrito</h1>

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
<?php
$total = 0;
$data = $model->getCartItems();
$items = $data['items'];
foreach ($data['products'] as $product)
{
    $quantity = $items[$product['id']];
    $price = $product['price'] * $quantity;
    $total += $price;
    echo "<tr>
    <td>$product[name]</td>
    <td>
        <input type='hidden' id='unit-price-$product[id]' value='$product[price]'>
        <input class='update' type='number' min='1' data-url='/shoppingCart/update/$product[id]'
                                                    data-item-id='$product[id]'
                                                    data-old-value='$quantity' value='$quantity'>
    </td>
    <td>$price</td>
    <td><a class='delete' href='/shoppingCart/delete/$product[id]'>Delete</a></td>
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
