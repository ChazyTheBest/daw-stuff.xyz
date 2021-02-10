<?php

use framework\App;

$this->title = 'Product List';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <table id="product-table" class="table">
            <thead>
            <tr>
                <th><?= App::t('table', 'th_enabled') ?></th>
                <th><?= App::t('table', 'th_image') ?></th>
                <th><?= App::t('table', 'th_name') ?></th>
                <th><?= App::t('table', 'th_price') ?></th>
                <th><?= App::t('table', 'th_discount') ?></th>
                <th><?= App::t('table', 'th_category') ?></th>
                <th><?= App::t('table', 'th_subcategory') ?></th>
                <th><?= App::t('table', 'th_options') ?></th>
            </tr>
            </thead>
        </table>

    </section>
