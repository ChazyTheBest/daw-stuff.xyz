<?php

namespace controllers;

use Exception;
use framework\App;
use models\SignupForm;
use models\User;
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
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'manage', 'view', 'delete', 'signupstaff' ],
                        'roles' => [ 'staff', 'admin' ]
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
     * Displays the signup page and creates a new user.
     *
     * @return mixed
     * @throws Exception
     */
    public function actionSignup(): string
    {
        $model = new SignupForm();
        if ($this->getIsAjax() && $model->load($_POST))
            return $model->signup()
                ? $this->redirect('home')
                : $this->inform(App::t('error', 'signup_failed'));

        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionSignupstaff(): string
    {
        $model = new SignupForm();
        if ($this->getIsAjax() && $model->load($_POST))
            return $model->signup(App::$user->role === 'admin' ? $_GET['role'] : '')
                ? $this->redirect('home')
                : $this->inform(App::t('error', 'signup_failed'));

        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionUpdate(): string
    {
        $model = new UserInfoForm();
        if ($this->getIsAjax() && $model->load($_POST))
            return $model->update()
                ? $this->redirect('reload')
                : $this->inform(App::t('error', 'update_failed'));

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionManage(): string
    {
        if ($this->getIsAjax())
        {
            $roles = [ 'customer' ];
            $buttons = [
                'names' => [
                    'create' => App::t('table', 'btn_signup'),
                    'reset' => App::t('table', 'btn_all'),
                    'filter' => [
                        App::t('table', 'td_status_0'),
                        App::t('table', 'td_status_9'),
                        App::t('table', 'td_status_10')
                    ]
                ],
                'create' => [ 'url' => '/user/signupstaff' ],
                'reset' => [ 'column' => 4 ],
                'filter' => [
                    [ 'column' => 4, 'term' => App::t('table', 'td_status_0') ],
                    [ 'column' => 4, 'term' => App::t('table', 'td_status_9') ],
                    [ 'column' => 4, 'term' => App::t('table', 'td_status_10') ]
                ]
            ];

            if (App::$user->role === 'admin')
            {
                $roles = [ 'customer', 'staff' ];
                $buttons = [
                    'names' => [
                        'create' => App::t('table', 'btn_signup'),
                        'reset' => App::t('table', 'btn_all'),
                        'filter' => [
                            App::t('table', 'btn_staff'),
                            App::t('table', 'btn_customers'),
                            App::t('table', 'td_status_0'),
                            App::t('table', 'td_status_9'),
                            App::t('table', 'td_status_10')
                        ]
                    ],
                    'create' => [ 'url' => '/user/signupstaff' ],
                    'reset' => [ 'columns' => [ 3, 4 ] ],
                    'filter' => [
                        [ 'columns' => [ 3 ], 'term' => 'staff' ],
                        [ 'columns' => [ 3 ], 'term' => 'customer' ],
                        [ 'columns' => [ 4 ], 'term' => App::t('table', 'td_status_0') ],
                        [ 'columns' => [ 4 ], 'term' => App::t('table', 'td_status_9') ],
                        [ 'columns' => [ 4 ], 'term' => App::t('table', 'td_status_10') ]
                    ]
                ];
            }

            header('Content-type: application/json');

            return json_encode([
                'query' => User::getList($roles),
                'texts' => [
                    'title' => [
                        'view' => App::t('table', 'td_view_user'),
                        'delete' => App::t('table', 'td_delete_user')
                    ],
                    'status' => User::getStatusList(),
                    'buttons' => $buttons
                ],
                'lang' => ''
            ]);
        }

        return $this->render('manage');
    }

    public function actionView(int $id): string
    {
        $user = User::findById($id);
        if (!$user || !$user instanceof User)
            return $this->error('404', [
                'message' => App::t('error', '404_user')
            ]);

        if (App::$user->role === 'staff' && ($user->role === 'staff' || $user->role === 'admin'))
            return $this->error('403');

        $model = new UserInfoForm();
        if ($this->getIsAjax() && $model->load($_POST))
            return $model->update($id)
                ? $this->redirect('reload')
                : $this->inform(App::t('error', 'update_failed'));

        return $this->render('view', [
            'model' => $model,
            'user' => $user
        ]);
    }

    public function actionDelete(int $id): string
    {
        if (!$this->getIsAjax())
            return $this->error('405', [
                'message' => App::t('error', '405_ajax')
            ]);

        $model = User::findById($id);
        if ($id < 1 || !$model)
            return json_encode([
                'message' => App::t('error', '404_user')
            ]);

        $model->disable([ 'id' => $id ]);

        return $this->redirect('reload');
    }
}
