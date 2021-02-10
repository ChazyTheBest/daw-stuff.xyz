<?php

/* @var $model ProductForm */

use models\ProductForm;

$this->title = 'New Product';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <form id="ProductForm" class="dropzone" action="/product/create" method="POST" enctype="multipart/form-data">
            <?= $model->getFormFields() ?>

            <input id="submit-all" type="submit" value="Create">
        </form>

    </section>
