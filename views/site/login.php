<?php

/* @var $model LoginForm */

use models\LoginForm;

$this->title = 'Login';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <form id="login" action="/site/login" method="POST">
            <?= $model->getFormFields() ?>

            <input type="submit" value="Login">
        </form>

    </section>
