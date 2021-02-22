<?php

/* @var $cats array list of categories */
/* @var $pagination array product pagination */
/* @var $cat_id int the category id */
/* @var $sub_id int the subcategory id*/

use framework\App;
use models\Category;
use models\Product;

$this->title = 'Shop';

$total = $pagination['total'];
$limit = App::$config['pagination']['limit'];
$pages = ceil($total / $limit);
$page = $pagination['page'];
$offset = ($page - 1) * $limit;
$products = $pagination['products'];
$prev = $page - 1;
$next = $page + 1;

?>
    <section class="bg-light p-4 d-flex flex-wrap">

        <h1 class="w-100 mb-4"><?= $this->title ?></h1>

        <aside class="col-xl-3">
            <div class="accordion" id="accordionExample">
<?php foreach ($cats as $cat): ?>
                <div class="card">
                    <div class="card-header" id="heading<?= $cat['id'] ?>">
                        <h2 class="mb-0">
                            <a class="btn btn-link btn-block text-left" href="/shop/index/<?= $cat['id'] ?>" data-toggle="collapse"
                                                                                           data-target="#collapse<?= $cat['id'] ?>"
                                                                                           aria-expanded="<?= $cat_id === $cat['id'] ? 'true' : 'false' ?>"
                                                                                           aria-controls="collapse<?= $cat['id'] ?>">
                                <span><?= $cat['name'] ?></span>
                            </a>
                        </h2>
                    </div>

                    <div id="collapse<?= $cat['id'] ?>" class="list-group collapse<?= $cat_id === $cat['id'] ? ' show' : '' ?>" aria-labelledby="heading<?= $cat['id'] ?>" data-parent="#accordionExample">
<?php foreach (Category::getAll($cat['id']) as $subcat): ?>
                        <a class="list-group-item d-flex justify-content-between align-items-center<?= $subcat['id'] === $sub_id ? ' active' : '' ?>" href="/shop/index/<?= $cat['id'] ?>/<?= $subcat['id'] ?>">
                            <span><?= $subcat['name'] ?></span>
                            <span class="badge badge-primary badge-pill justify-end"><?= Product::count([ 'category_id' => $cat['id'], 'subcategory_id' => $subcat['id'], 'status' => Product::STATUS_ACTIVE ]) ?></span>
                        </a>
<?php endforeach; ?>
                    </div>
                </div>
<?php endforeach; ?>
            </div>
        </aside>

        <article class="d-flex flex-wrap justify-content-around col-xl-9">
            <p class="w-100">There are <?= $total ?> products.</p>
<?php foreach ($products as $product): $img = $product['image']; ?>
            <div class="card my-2 mx-2" style="width: 18rem;">
                <a href="/product/index/<?= $product['id'] ?>">
                    <img class="card-img-top" src="<?= str_starts_with($img, 'https') ? $img : "/img/products/$img" ?>" alt="Product #<?= $product['id'] ?>">
                </a>
                <div class="card-body">
                    <a href="/product/index/<?= $product['id'] ?>"><h5 class="card-title"><?= $product['name'] ?></h5></a>
                    <p class="card-text">
                        <span><?= $product['description'] ?></span><br>
                        <span>Price: <?= $product['price'] ?> &euro;</span>
                    </p>
                    <form class="input-group" action="/shoppingCart/add/<?= $product['id'] ?>" method="POST">
                        <input type="number" class="form-control" name="quantity" min="1" max="999" value="1" aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Add to Cart</button>
                        </div>
                    </form>
                </div>
            </div>
<?php endforeach; ?>
            <nav aria-label="Page navigation products" class="d-flex w-100 justify-content-between align-items-center mt-4">
                <p>Showing <?= $offset + 1 ?> to <?= min(($offset + $limit), $total) ?> of <?= $total ?></p>
                <ul class="pagination">
                    <li class="page-item<?= $page < 2 ? ' disabled' : '' ?>">
                        <a class="page-link" href="<?= $page < 2 ? '3' : "?page=$prev" ?>">Previous</a>
                    </li>
<?php for($i = 1; $i <= $pages; $i++ ): ?>
                    <li class="page-item<?= $page === $i ? ' active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"> <?= $i; ?> </a>
                    </li>
<?php endfor; ?>
                    <li class="page-item<?= $page >= $pages ? ' disabled' : '' ?>">
                        <a class="page-link" href="<?= $page >= $pages ? '#' : "?page=$next" ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </article>

    </section>
