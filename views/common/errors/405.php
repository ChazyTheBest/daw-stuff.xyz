<?php

/* @var $message string the error message */

http_response_code(405);

$this->title = '405 Method Not Allowed';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <p><?= $message ?></p>

    </section>
