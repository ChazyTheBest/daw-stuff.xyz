<?php

/* @var $model ProductForm */
/* @var $product Product */

use models\Product;
use models\ProductForm;

$this->title = 'Update Product';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <form id="ProductForm" class="dropzone" action="/product/update/<?= $product->id ?>" method="POST">
            <?= $model->getFormFields([ Product::class, 'findById' ], $product->id) ?>

            <input id="submit-all" type="submit" value="Update">
        </form>

    </section>
