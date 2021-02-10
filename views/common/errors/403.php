<?php

/* @var $message string the error message */

http_response_code(403);

$this->title = '403 Forbidden';

?>
    <section class="bg-light p-4">

        <h1><?= $this->title ?></h1>

        <p><?= $message ?></p>

    </section>
