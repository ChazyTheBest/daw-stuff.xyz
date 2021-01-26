<?php

/* @var $model \models\LoginForm */

$this->title = 'Login';

?>
<section>
    <h1><?= $this->title ?></h1>

    <form id="login" action="/site/login" method="POST">
        <?= $model->getFormFields() ?>

        <input type="submit" value="Login">
    </form>
</section>
