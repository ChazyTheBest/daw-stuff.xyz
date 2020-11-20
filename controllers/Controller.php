<?php

namespace controllers;

use framework\UserSession;

class Controller
{
    private string $controller;
    private string $title;

    public function __construct(string $string)
    {
        $this->controller = $string;
    }

    // TODO: implement a view system
    public function render(string $view, array $params = []): string
    {
        $auth = UserSession::getInstance();

        // default title
        // TODO: move value to config
        $this->title = 'Daw Stuff';

        header('Content-Type: Text/HTML; Charset=UTF-8');

        extract($params);

        ob_start();

        include(dirname(__DIR__) . "/views/$this->controller/$view.php");
        $content = ob_get_contents();

        ob_clean();

        include dirname(__DIR__) . '/views/layouts/main.php';
        $main = ob_get_contents();

        ob_end_clean();

        return $main;
    }

    // TODO: implement error messages
    protected function go(array $msg): string
    {
        header('Content-type: application/json');
        return json_encode([
            'status' => $msg['status'],
            'message' => $msg['msg'],
            'redirect' => $msg['redirect']
        ]);
    }
}
