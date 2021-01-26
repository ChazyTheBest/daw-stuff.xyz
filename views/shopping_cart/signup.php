<?php

/* @var $model \models\SignupForm */

$this->title = 'Registrarse';

?>
<section>
    <h1><?= $this->title ?></h1>

    <form id="signup" action="/shoppingCart/signup" method="POST">
        <?= $model->getFormFields() ?>

        <input type="submit" value="Signup">
    </form>
</section>
