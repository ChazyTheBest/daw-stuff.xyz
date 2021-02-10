<?php

/* @var $message string the error message */

http_response_code(401);

$this->title = '401 Unauthorized';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <p><?= $message ?></p>

    </section>
