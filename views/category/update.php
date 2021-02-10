<?php

/* @var $model CategoryForm */
/* @var $category Category */

use models\Category;
use models\CategoryForm;

$this->title = 'Update Category';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <form id="CategoryForm" action="/category/update/<?= $category->id ?>" method="POST">
            <?= $model->getFormFields([ Category::class, 'findById' ], $category->id) ?>

            <input type="submit" value="Update">
        </form>

    </section>
