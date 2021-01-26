<?php

namespace controllers;

use framework\UserSession;
use models\Cart;
use models\Product;
use models\SignupForm;

final class ShoppingCartController extends Controller
{
    /**
     * SiteController constructor.
     */
    public function __construct()
    {
        parent::__construct('shopping_cart');
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
                        'actions' => [ 'thanks' ],
                        'roles' => [ '@' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'index', 'add', 'update', 'delete' ],
                        'roles' => [ '?', '@' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'signup' ],
                        'roles' => [ '?' ]
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
        return $this->render('index', [
            'model' => new Cart()
        ]);
    }

    public function actionAdd(int $id): string
    {
        if ($id < 1 || !Product::findById($id))
            return $this->render('error');

        $model = new Cart();
        $model->addItem($id, $_POST['quantity'] ?? 1);

        header('Content-type: application/json');
        return json_encode([
            'redirect' => 'reload'
        ]);
    }

    public function actionUpdate(int $id): string
    {
        if ($id < 1 || !Product::findById($id))
            return $this->render('error');

        $model = new Cart();

        $data = $_GET['op'] ?? $_POST['quantity'] ?? false;
        if ($data && $data === 'up' || $data === 'down')
        {
            $model->addItem($id, $data === 'up' ? 1 : -1);
        }

        else if ($data && $data > 0)
        {
            $model->setItemQuantity($id, $data);
        }

        header('Content-type: application/json');
        return json_encode([
            'redirect' => 'redirect'
        ]);
    }

    public function actionDelete(int $id): string
    {
        $model = new Cart();
        $model->deleteItem($id);

        header('Content-type: application/json');
        return json_encode([
            'redirect' => 'redirect'
        ]);
    }

    public function actionSignup(): string
    {
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_CART;
        if ($model->load($_POST))
            return $model->signupWithInfo()
                ? $this->go([ 'status' => 'success', 'msg' => '', 'redirect' => '/order/create' ])
                : $this->go([ 'status' => 'error', 'msg' => 'signup_failed', 'redirect' => '' ]);

        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionThanks(): string
    {
        (new Cart())->processCart();

        return $this->render('thanks');
    }
}
