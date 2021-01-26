<?php

/* @var $model \models\SignupForm */

$this->title = 'Signup';

?>
<section>
    <h1><?= $this->title ?></h1>

    <form id="signup" action="/user/signup" method="POST">
        <?= $model->getFormFields() ?>

        <input type="submit" value="Signup">
    </form>
</section>
