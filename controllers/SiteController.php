<?php

namespace controllers;

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
                        'actions' => [ 'index', 'error' ],
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
        if ($model->load($_POST))
        {
            $msg = '';
            $redirect = '';

            if ($model->login())
            {
                $status = 'success';
                $redirect = 'home';
            }

            else
            {
                $status = 'error';
                $msg = 'login_failed';
            }

            return $this->go([
                'status' => $status,
                'msg' => $msg,
                'redirect' => $redirect
            ]);
        }

        // is this really necessary?
        //$model->password = '';

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
