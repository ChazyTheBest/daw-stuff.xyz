<?php

use framework\App;

$this->title = 'User List';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <table id="users-table" class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th><?= App::t('table', 'th_name') ?></th>
                <th><?= App::t('table', 'th_role') ?></th>
                <th><?= App::t('table', 'th_status') ?></th>
                <th><?= App::t('table', 'th_created_at') ?></th>
                <th><?= App::t('table', 'th_options') ?></th>
            </tr>
            </thead>
        </table>

    </section>
