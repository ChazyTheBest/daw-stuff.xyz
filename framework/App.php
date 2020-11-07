<?php

namespace framework;

final class App
{
    private array $config;

    /**
     * App constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    // this is an attempt to replicate a basic functionality of the Yii2 framework
    public function run(): void
    {
        Autoloader::register();

        // missing routing
        // missing proper controller/model system
        // missing middleware framework
        // hacks incoming :)

        $this->config['httpMethod'] = $_SERVER['REQUEST_METHOD'];
        $this->config['uri'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        echo $this->handleRequest($this->config['uri'] === '/' ? '/site/index' : $this->config['uri']);
    }

    // TODO: implement allowed methods
    private function handleRequest(string $resource): ?string
    {
        // after this we can get an instance statically
        $auth = new UserSession($this->config);

        // split the requested resource into controller and action
        $components = explode('/', $resource);

        $c_name = "\\controllers\\" . ucfirst($components[1]) . 'Controller';

        // if the controller does not exist, display an error
        if (!class_exists($c_name))
            return (new \controllers\Controller('site'))->render('error');

        // if the action is not specified, display an error
        if (!isset($components[2]))
            return (new \controllers\Controller('site'))->render('error');

        $controller = new $c_name;
        $action = $components[2];

        // TODO: URI params (ie: /user/view/1 or /user/view/username)
        // TODO: rewrite to short url (ie: /site/index -> /index)

        $behaviors = $controller->behaviors();
        foreach ($behaviors['access']['rules'] as $rule)
        {
            // check if the action exists
            if (in_array($action, $rule['actions']) && $rule['allow'])
            {
                $method = 'Action' . ucfirst($action);

                // the action can be performed by both authenticated and non-authenticated users
                if (in_array('?', $rule['roles']) && in_array('@', $rule['roles']))
                    return $controller->$method($components[3] ?? null);

                // the action requires that the user is not authenticated
                if (in_array('?', $rule['roles']) && !$auth->isLoggedIn())
                    return $controller->$method();

                // the action requires that the user is authenticated
                if (in_array('@', $rule['roles']) && $auth->isLoggedIn())
                    return $controller->$method();

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
        return (new \controllers\Controller('site'))->render('error');
    }
}
