<?php

namespace controllers;

use Exception;
use models\SignupForm;
use models\UserInfoForm;

final class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct('user');
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
                        'allow' => true,
                        'actions' => [ 'signup' ],
                        'roles' => [ '?' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'index', 'update' ],
                        'roles' => [ '@' ]
                    ]
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

    /**
     * Displays the welcome page.
     *
     * @return mixed
     */
    public function actionWelcome(): string
    {
        return $this->render('welcome');
    }

    /**
     * Displays the signup page and creates a new user.
     *
     * @return mixed
     * @throws Exception
     */
    public function actionSignup(): string
    {
        $model = new SignupForm();
        if ($model->load($_POST))
            return $model->signup()
                ? $this->go([ 'status' => 'success', 'msg' => '', 'redirect' => 'home' ])
                : $this->go([ 'status' => 'error', 'msg' => 'signup_failed', 'redirect' => '' ]);

        return $this->render('signup', [
            'model' => $model
        ]);
    }

    // TODO: update user info
    public function actionUpdate(): string
    {
        $model = new UserInfoForm();
        if ($model->load($_POST))
            return $model->update()
                ? $this->go([ 'status' => 'success', 'msg' => '', 'redirect' => 'reload' ])
                : $this->go([ 'status' => 'error', 'msg' => 'update_failed', 'redirect' => '' ]);

        return $this->render('update', [
            'model' => $model
        ]);
    }
}
