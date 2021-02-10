<?php

use framework\App;

$this->title = 'Order List';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <table id="order-table" class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th><?= App::t('table', 'th_reference') ?></th>
                <th>Total</th>
                <th><?= App::t('table', 'th_status') ?></th>
                <th><?= App::t('table', 'th_created_at') ?></th>
                <th><?= App::t('table', 'th_created_by') ?></th>
                <th><?= App::t('table', 'th_options') ?></th>
            </tr>
            </thead>
        </table>

    </section>
