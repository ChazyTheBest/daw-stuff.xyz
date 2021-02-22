<?php

/* @var $model Product */

use models\Product;

$this->title = $model->name;

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <article>
            <img src="/img/products/<?= $model->image ?>" alt="">
            <p><?= $model->description ?></p>
            <p>Price: <?= $model->price ?> &euro;</p>
            <form id="product-add" action="/shoppingCart/add/<?= $model->id ?>" method="POST">
                <input type="number" name="quantity" min="1" max="999" value="1"><input type="submit" value="Add to cart">
            </form>
        </article>

    </section>
