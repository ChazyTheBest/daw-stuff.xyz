<?php

use framework\App;

$this->title = 'Category List';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <table id="category-table" class="table">
            <thead>
            <tr>
                <th><?= App::t('table', 'th_enabled') ?></th>
                <th><?= App::t('table', 'th_image') ?></th>
                <th><?= App::t('table', 'th_name') ?></th>
                <th><?= App::t('table', 'th_type') ?></th>
                <th><?= App::t('table', 'th_options') ?></th>
            </tr>
            </thead>
        </table>

    </section>
