<?php

/* @var $model UserInfoForm */

use models\UserInfo;
use models\UserInfoForm;

$this->title = 'Update';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <form id="UserInfoForm" action="/user/update" method="POST">
            <?= $model->getFormFields([ UserInfo::class, 'findById' ]) ?>

            <input type="submit" value="Update">
        </form>

    </section>
