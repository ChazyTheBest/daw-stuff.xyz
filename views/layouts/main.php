<?php

// TODO: implement page title
// TODO: implement translation system
// TODO: implement asset loading

/* @var $content string */
/* @var $auth \framework\UserSession */

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
    <script src="/js/shop.js"></script>
</head>
<body>

<header class="container mx-auto my-2 p-0">
    <h1>DAW STUFF</h1>

    <nav>
        <ul>
            <li><a href="/site/index">Inicio</a></li>
            <li><a href="/shop/index">Tienda</a></li>
            <?php
            if ($auth->isLoggedIn())
            {
                echo '<li><a href="/user/index">Usuario</a><ul><li><a href="/user/update">Actualizar</a></li></ul></li>', "\n",
                     '<li><a href="/site/logout">Salir</a></li>', "\n";
            }

            else
            {
                echo '<li><a href="/site/login">Login</a></li>', "\n",
                     '<li><a href="/user/signup">Registrarse</a></li>', "\n";
            }
            ?>
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
