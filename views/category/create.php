<?php

/* @var $model CategoryForm */

use models\CategoryForm;

$this->title = 'New Category';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <form id="CategoryForm" action="/category/create" method="POST">
            <?= $model->getFormFields() ?>

            <input type="submit" value="Create">
        </form>

    </section>
