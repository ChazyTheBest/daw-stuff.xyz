<?php

// TODO: implement translation system

/* @var $model \models\Product */

$this->title = 'Product';

?>
<section>
    <h1><?= $this->title ?></h1>

    <article>
        <h2><?= $model->name ?></h2>
        <img src="/img/products/<?= $model->image ?>" alt="">
        <p><?= $model->description ?></p>
        <form id="product-add" action="/shoppingCart/add/<?= $model->id ?>" method="POST">
            <input type="number" name="quantity" value="1"><input type="submit" value="Add to cart">
        </form>
    </article>
</section>
