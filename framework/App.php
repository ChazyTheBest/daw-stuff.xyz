<?php

namespace framework;

use controllers\Controller;
use models\User;

final class App
{
    public static array $config;
    public static ?User $user;

    /**
     * App constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        Autoloader::register();
        self::$config = $config;
        $auth = new UserSession();
        self::$user = $auth->isLoggedIn() ? User::findById($_SESSION['user_id']) : null;
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
        // after this we can get an instance statically
        $auth = UserSession::getInstance();

        // split the requested resource into controller and action
        $components = explode('/', $resource);

        $c_name = "\\controllers\\" . ucfirst($components[1]) . 'Controller';

        // if the controller does not exist, display an error
        if (!class_exists($c_name))
            return (new Controller('site'))->render('error');

        // TODO: URI params (ie: /user/view/1 or /user/view/username)
        // TODO: rewrite to short url (ie: /site/index -> /index)
        $action = $components[2];

        // if the action is not specified, display an error
        if (!isset($action))
            return (new Controller('site'))->render('error');

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
                    return $controller->$method($components[3] ?? null); // WIP

                // the action requires that the user is not authenticated
                if (in_array('?', $rule['roles']) && !$auth->isLoggedIn())
                    return $controller->$method();

                // the action requires that the user is authenticated
                if (in_array('@', $rule['roles']) && $auth->isLoggedIn())
                    return $controller->$method($components[3] ?? null);

                else
                {
                    header('Content-type: application/json');
                    return json_encode([
                        'msg' => 'You need to be authenticated to perform this action'
                    ]);
                }
            }
        }

        // the action does not exist, or is not valid, therefore display an error message
        return (new Controller('site'))->render('error');
    }

    // TODO: implement file-based translations
    public static function t(string $cat, string $name): string
    {
        return '';
    }
}
