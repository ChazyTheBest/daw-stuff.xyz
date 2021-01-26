<?php

/* @var $model \models\UserInfo */

$this->title = 'Update';

?>
<section>
    <h1><?= $this->title ?></h1>

    <form id="UserInfoForm" action="/user/update" method="POST">
        <?= $model->getFormFields() ?>

        <input type="submit" value="Signup">
    </form>
</section>
