<?php

/* @var $model UserInfoForm */
/* @var $user User */

use models\User;
use models\UserInfo;
use models\UserInfoForm;

$this->title = 'User Info';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <form id="UserInfoForm" action="/user/view/<?= $user->id ?>" method="POST">
            <?= $model->getFormFields([ UserInfo::class, 'findById' ], $user->id) ?>

            <input type="submit" value="Update">
        </form>

    </section>
