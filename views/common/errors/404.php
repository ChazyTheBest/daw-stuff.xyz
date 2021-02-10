<?php

/* @var $message string the error message */

http_response_code(404);

$this->title = '404 Not Found';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <p><?= $message ?></p>

    </section>
