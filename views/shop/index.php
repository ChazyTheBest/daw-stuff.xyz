<?php

// TODO: implement translation system

/* @var $cats array */
/* @var $pagination array */

$this->title = 'Tienda';

$total = $pagination['total'];
$limit = $pagination['limit'];
$pages = $pagination['pages'];
$page = $pagination['page'];
$offset = $pagination['offset'];
$products = $pagination['products'];

$prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';
$nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

?>
<section>
    <h1><?= $this->title ?></h1>

    <aside>
        <ul>
<?php
$html = '            ';
foreach ($cats as $cat)
{
    echo $html, '<li><a href="/shop/index/', $cat['id'], '">', $cat['name'], '</a></li>', "\n";
}
?>
        </ul>
    </aside>

    <article>
        <p>There are <?= $total ?> products.</p>
<?php
$html = '        ';
foreach ($products as $product)
{
    echo $html, '<a href="/product/index/', $product['id'], '"><figure><img src="/img/products/', $product['image'], '" alt=""><figcaption><p>', $product['name'],'</p></figcaption></figure></a>', "\n\n";
}

echo $html, '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' ', $nextlink, ' </p><p>displaying ', ($offset + 1), '-', min(($offset + $limit), $total), ' of ', $total, ' results</p></div>';
?>
    </article>
</section>
