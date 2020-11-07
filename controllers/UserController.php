<?php

namespace controllers;

final class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct('user');
    }

    public function behaviors()
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

    // TODO: update username and password
    public function actionUpdate(): string
    {
        // TODO: need a form for this
        //$model = User();

        return $this->render('update');
    }

    // TODO: add new entries to the user_list file
    public function actionSignup(): string
    {
        // TODO: need a form for this
        //$model = User();

        return $this->render('signup');
    }
}
