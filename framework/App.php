<?php

namespace framework;

use controllers\Controller;
use models\User;

final class App
{
    public static array $config;
    public static ?User $user;
    private static array $messages = [];

    /**
     * App constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        Autoloader::register();
        self::$config = $config;
        self::$user = (new UserSession($config['session']))->isLoggedIn() ? User::findById($_SESSION['user_id']) : null;
    }

    // this is an attempt to replicate a basic functionality of the Yii2 framework
    public function run(): void
    {
        // missing routing
        // missing proper controller/model system
        // missing middleware framework
        // hacks incoming :)

        self::$config['httpMethod'] = $_SERVER['REQUEST_METHOD'];
        self::$config['uri'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        echo $this->handleRequest(self::$config['uri'] === '/' ? '/site/index' : self::$config['uri']);
    }

    // TODO: implement allowed methods
    private function handleRequest(string $resource): string
    {
        $auth = UserSession::getInstance();

        // split the requested resource into controller and action
        $components = explode('/', $resource);

        $c_name = "\\controllers\\" . ucfirst($components[1]) . 'Controller';

        // if the controller does not exist, display an error
        if (!class_exists($c_name))
            return (new Controller('site'))->error('404');

        // TODO: rewrite to short url (ie: /site/index -> /index)
        $action = $components[2];
        $p = $components;

        // if the action is not specified, display an error
        if (!isset($action))
            return (new Controller('site'))->error('404');

        $controller = new $c_name;
        $behaviors = $controller->behaviors();
        foreach ($behaviors['access']['rules'] as $rule)
        {
            // check if the action exists
            if (in_array($action, $rule['actions']) && $rule['allow'])
            {
                $method = 'action' . ucfirst($action);

                // the action can be performed by both authenticated and non-authenticated users
                if (in_array('?', $rule['roles']) && in_array('@', $rule['roles']))
                    return $controller->$method(isset($p[3]) ? (int) $p[3] : null, isset($p[4]) ? (int) $p[4] : null); // WIP

                // the action requires that the user is not authenticated
                if (in_array('?', $rule['roles']) && !$auth->isLoggedIn())
                    return $controller->$method();

                // the action requires that the user is authenticated and authorized
                if ($auth->isLoggedIn())
                {
                    if (in_array(App::$user->role, $rule['roles']))
                    {
                        // check ownership
                        if (App::$user->role === 'customer' && isset($rule['roleCheck']))
                        {
                            $model = $rule['roleCheck'][0]::{$rule['roleCheck'][1]}(isset($p[3]) ? (int) $p[3] : null);
                            if (!$model)
                                return (new Controller('site'))->error('404'); // not found

                            if ($model->created_by !== App::$user->id)
                                return (new Controller('site'))->error('403'); // not authorized
                        }
                    }

                    else if (!in_array('@', $rule['roles']))
                        return (new Controller('site'))->error('403'); // not authorized

                    // else fix controller rules

                    return $controller->$method(isset($p[3]) ? (int) $p[3] : null);
                }

                else
                {
                    return (new Controller('site'))->error('401'); // not authenticated
                }
            }
        }

        // the action does not exist, or is not valid, therefore display an error message
        return (new Controller('site'))->error('404');
    }

    /**
     * File-based translated text strings
     *
     * @param string $cat the name of the file
     * @param string $name the key of the text string
     * @param array $params the optional params to be replaced (%s -> $var1)
     * @return string the text string
     */
    public static function t(string $cat, string $name, array $params = []): string
    {
        if (!isset(self::$messages[$cat]))
        {
            $file = dirname(__DIR__) . '/messages/' . self::$config['lang'] . "/$cat.php";
            if (!is_file($file))
                return '';

            $messages[$cat] = require $file;
        }

        return $params === [] ? ($messages[$cat][$name] ?? '') : self::replaceVar($messages[$cat][$name] ?? '', $params);
    }

    /**
     * Replaces the placeholders in a string for final values
     *
     * @param string $text the string with placeholders
     * @param array $params the params that replace the placeholders
     * @return string the final text string
     */
    private static function replaceVar(string $text, array $params): string
    {
        if ($text === '')
            return '';

        return sprintf($text, ...$params); // TODO improve this
    }
}
