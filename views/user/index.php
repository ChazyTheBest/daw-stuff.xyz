<?php

// TODO: implement translation system
// TODO: pass the User model to display data instead of using $_SESSION

$this->title = 'Bienvenido';

?>
<section class="bg-light p-4">

    <h1>User Index</h1>

    <p>Bienvenido: <?= $_SESSION['username'] ?></p>

</section>
