<?php

// TODO: implement page title
// TODO: implement translation system
// TODO: implement asset loading

/* @var $content string */
/* @var $auth \framework\UserSession */
/* @var $cart \models\Cart */

$count = $cart->getItemCount();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $this->title ?></title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/js/form.js"></script>
    <script src="/js/cart.js"></script>
</head>
<body>

<header class="container mx-auto my-2 p-0">
    <nav>
        <a href="/site/contact">Contact</a>
        <ul id="menu-top-right">
            <li><select id="lang"></select></li>
            <?php
            if ($auth->isLoggedIn())
            {
                echo '<li><a href="/user/index">User</a><ul><li><a href="/user/update">Update</a></li><li><a href="/order/index">Orders</a></li></ul></li>', "\n",
                     '<li><a href="/site/logout">Logout</a></li>', "\n";
            }

            else
            {
                echo '<li><a href="/site/login">Login</a></li>', "\n",
                     '<li><a href="/user/signup">Signup</a></li>', "\n";
            }
            ?>
            <li><?= $count > 0 ? '<a href="/shoppingCart/index" id="cart-active">Cart (' . $count . ')</a>' : '<span id="cart-empty">Cart (0)</span>' ?></li>
        </ul>
        <ul id="menu-bottom">
            <li><a href="/site/index">Home</a></li>
            <li><a href="/shop/index">Shop</a></li>
            <li id="search-right"><input type="text" placeholder="Search our catalog" name="search"></li>
        </ul>
    </nav>
</header>

<main class="container">
    <?= $content ?>
</main>

<footer class="container bg-light mt-3">
    <div class="row px-4 py-3">
        <p class="m-0">&copy; <?= date('Y') ?> <strong>daw-stuff.xyz</strong></p>
        <p class="m-0 ml-auto">Webapp by chazy</p>
    </div>
</footer>

</body>
</html>
