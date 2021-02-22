<?php

namespace controllers;

use Exception;
use framework\App;
use models\LoginForm;

final class SiteController extends Controller
{
    /**
     * SiteController constructor.
     */
    public function __construct()
    {
        parent::__construct('site');
    }

    public function behaviors(): array
    {
        return
        [
            'access' =>
            [
                'rules' =>
                [
                    [
                        'actions' => [ 'login' ],
                        'allow' => true,
                        'roles' => [ '?' ]
                    ],
                    [
                        'actions' => [ 'logout' ],
                        'allow' => true,
                        'roles' => [ '@' ]
                    ],
                    [
                        'actions' => [ 'index' ],
                        'allow' => true,
                        'roles' => [ '?', '@' ]
                    ]
                ]
            ],
            'verbs' =>
            [
                'actions' =>
                [
                    'logout' => [ 'post' ]
                ]
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionLogin(): string
    {
        $model = new LoginForm();

        if ($this->getIsAjax())
        {
            try
            {
                $model->load($_POST);

                return $model->login()
                    ? $this->redirect('home')
                    : $this->inform(App::t('error', 'login_failed'));
            }

            catch (Exception $e)
            {
                return $this->inform($e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionLogout(): void
    {
        // Unset all session values
        $_SESSION = [];

        // Delete the actual cookie
        setcookie(session_name(), '', time() - 3600);

        // Destroy the session
        session_destroy();

        header('Location: /');
    }
}
