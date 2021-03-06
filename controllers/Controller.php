<?php

namespace controllers;

use framework\App;
use framework\UserSession;
use models\BrowserCart;
use models\UserCart;

class Controller
{
    private string $controller;
    private string $title;

    public function __construct(string $name)
    {
        $this->controller = $name;
    }

    // TODO: implement a view system
    public function render(string $view, array $params = []): string
    {
        $components = explode('/', App::$config['uri'] === '/' ? '/site/index' : App::$config['uri']);
        $controller = $components[1];
        $action = $components[2];
        $auth = UserSession::getInstance();

        // default title
        // TODO: move value to config
        $this->title = 'Daw Stuff';

        header('Content-Type: Text/HTML; Charset=UTF-8');

        extract($params);
        $cart = UserSession::getInstance()->isLoggedIn() ? new UserCart() : new BrowserCart($_COOKIE['items'] ?? []);

        ob_start();

        include(dirname(__DIR__) . "/views/$this->controller/$view.php");
        $content = ob_get_contents();

        ob_clean();

        include dirname(__DIR__) . '/views/layouts/main.php';
        $main = ob_get_contents();

        ob_end_clean();

        return $main;
    }

    public function error(string $view, array $params = []): string
    {
        $this->controller = 'common/errors';

        if ($params === [])
            $params = [ 'message' => App::t('error', $view . '_default') ];

        return $this->render($view, $params);
    }

    protected function inform(string $msg): string
    {
        header('Content-type: application/json');
        return json_encode([
            'message' => $msg
        ]);
    }

    protected function redirect(string $url): string
    {
        header('Content-type: application/json');
        return json_encode([
            'redirect' => $url
        ]);
    }

    protected function getIsAjax(): bool
    {
        return (apache_request_headers()['X-Requested-With'] ?? '') === 'XMLHttpRequest';
    }
}
