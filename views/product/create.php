<?php

/* @var $model ProductForm */

use framework\App;
use models\Category;
use models\ProductForm;

$this->title = 'New Product';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <form id="ProductForm" class="upload" action="/product/create" method="POST" enctype="multipart/form-data">
            <div id="myDropzone" class="dropzone dropzone-prop">
                <div class="dz-message" data-dz-message>
                    <span class="message">Drop files here or click to upload.</span>
                    <span class="note">(This is just a demo dropzone. Selected files are not actually uploaded.)</span>
                </div>
                <div class="fallback">
                    <input type="file" name="images" multiple>
                </div>
            </div>

            <label for="name"><?= $model->attributeLabel('name') ?></label>
            <input id="name" type="text" name="ProductForm[name]">

            <label for="description"><?= $model->attributeLabel('description') ?></label>
            <input id="description" type="text" name="ProductForm[description]">

            <label for="price"><?= $model->attributeLabel('price') ?></label>
            <input id="price" type="text" name="ProductForm[price]">

            <label for="discount"><?= $model->attributeLabel('discount') ?></label>
            <input id="discount" type="text" name="ProductForm[discount]">

            <label for="category"><?= $model->attributeLabel('category') ?></label>
            <select id="category" class="cat" name="ProductForm[category]">
                <option><?= App::t('form', 'select_cat') ?></option>
                <?php foreach (Category::getAll() as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="subcategory"><?= $model->attributeLabel('subcategory') ?></label>
            <select id="subcategory" class="sub" name="ProductForm[subcategory]">
                <option><?= App::t('form', 'select_cat') ?></option>
            </select>

            <input type="submit" value="Create">
        </form>

    </section>
