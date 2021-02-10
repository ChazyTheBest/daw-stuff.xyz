<?php

/* @var $model SignupForm */

use models\SignupForm;

$this->title = 'Signup';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <form id="signup" action="" method="POST">
            <?= $model->getFormFields() ?>

            <input type="submit" value="Signup">
        </form>

    </section>
