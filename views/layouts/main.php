<?php

// TODO: implement asset loading

/* @var $content string the page content */
/* @var $auth UserSession */
/* @var $cart BrowserCart|UserCart */
/* @var $controller string */
/* @var $action string */

use framework\App;
use framework\UserSession;
use models\BrowserCart;
use models\UserCart;

$count = $cart->getItemCount();

?>
<!DOCTYPE html>
<html lang="<?= App::$config['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $this->title ?></title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="/js/functions.js"></script>

<?php if ($controller === 'shoppingCart'): ?>
    <script src="/js/cart.js"></script>
<?php elseif ($action === 'manage'): ?>
    <script src="https://cdn.datatables.net/v/bs4/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/r-2.2.7/sb-1.0.1/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.21/dataRender/datetime.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/natural.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/r-2.2.7/sb-1.0.1/datatables.min.css"/>
<?php if ($controller === 'user'): ?>
    <script src="/js/user-list.js"></script>
<?php elseif ($controller === 'category'): ?>
    <script src="/js/category-list.js"></script>
<?php elseif ($controller === 'product'): ?>
    <script src="/js/product-list.js"></script>
<?php elseif ($controller === 'order'): ?>
    <script src="/js/order-list.js"></script>
<?php endif; ?>
<?php elseif ($action === 'create' || $action === 'update' && $controller === 'category' || $controller === 'product'): ?>
    <link rel="stylesheet" href="/css/dropzone.min.css">
    <script src="/js/dropzone.min.js"></script>
    <script src="/js/img-upload.js"></script>
<?php endif; ?>
    <script src="/js/form.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<header class="container mx-auto my-2 p-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">MyShop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/site/index">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shop/index">Shop</a>
                </li>
<?php
if ($auth->isLoggedIn()):
    if (App::$user->role === 'admin' || App::$user->role === 'staff'):
?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        User
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/user/index">User</a>
                        <a class="dropdown-item" href="/user/update">Update</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/site/logout">Logout</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Management
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/user/manage">Users</a>
                        <!-- nested
                            <a href="/user/signupstaff">Signup Staff</a>
                         -->
                        <a class="dropdown-item" href="/category/manage">Categories</a>
                        <a class="dropdown-item" href="/product/manage">Products</a>
                        <a class="dropdown-item" href="/order/manage">Orders</a>
                    </div>
                </li>
<?php else: ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="/user/index" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        User
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/user/update">Update</a>
                        <a class="dropdown-item" href="/order/index">Orders</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/site/logout">Logout</a>
                    </div>
                </li>
<?php endif; else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/site/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/user/signup">Signup</a>
                </li>
<?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/site/contact">Contact</a>
                </li>
                <li class="nav-item">
                    <?= $count > 0 ? '<a class="nav-link" href="/shoppingCart/index" id="cart-active">Cart (' . $count . ")</a>\n" : '<span class="nav-link" id="cart-empty">Cart (0)</span>', "\n" ?>
                </li>
            </ul>
            <form id="search" class="form-inline my-2 my-lg-0" action="/shop/search" method="POST">
                <select id="lang" class="custom-select mr-3">
<?php foreach (App::$config['supported_languages'] as $key => $name): ?>
                    <option value="<?= $key ?>"<?= App::$config['lang'] === $key ? ' selected' : '' ?>><?= $name ?></option>
<?php endforeach; ?>
                </select>
                <input class="form-control mr-sm-2" type="search" name="term" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>
</header>

<main class="container"><?= "\n\n$content\n" ?></main>

<footer class="container bg-light mt-3">
    <div class="row px-4 py-3">
        <p class="m-0">&copy; <?= date('Y') ?> <strong>daw-stuff.xyz</strong></p>
        <p class="m-0 ml-auto">Webapp by chazy</p>
    </div>
</footer>

</body>
</html>
