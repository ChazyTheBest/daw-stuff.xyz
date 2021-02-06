<?php

namespace controllers;

use framework\App;
use framework\UserSession;
use models\BrowserCart;
use models\Product;
use models\SignupForm;
use models\UserCart;

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
        if (UserSession::getInstance()->isLoggedIn())
        {
            $model = new UserCart();
        }

        else
        {
            $model = new BrowserCart($_COOKIE['items'] ?? []);
        }
        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionAdd(int $id): string
    {
        header('Content-type: application/json');

        if (!$this->getIsAjax())
            return json_encode([ 'message' => 'This action only supports XMLHttpRequest.' ]);

        if ($id < 1 || !Product::findById($id))
            return json_encode([ 'message' => 'The product does not exist.' ]);

        if (UserSession::getInstance()->isLoggedIn())
        {
            $model = UserCart::findById($id);

            if (!$model)
            {
                $model = new UserCart();
                $model->product_id = $id;
                $model->quantity = $_POST['quantity'] ?? 1;
                $model->created_by = App::$user->id;
            }

            else
            {
                $model->quantity += $_POST['quantity'] ?? 1;
            }

            if ($model->quantity > 0 && $model->quantity < 1000)
                $model->save();
        }

        else
        {
            $model = new BrowserCart($_COOKIE['items'] ?? []);
            $model->addItem($id, $_POST['quantity'] ?? 1);
        }

        return json_encode([
            'redirect' => 'reload'
        ]);
    }

    public function actionUpdate(int $id): string
    {
        header('Content-type: application/json');

        if (!$this->getIsAjax())
            return json_encode([ 'message' => 'This action only supports XMLHttpRequest.' ]);

        if ($id < 1 || !Product::findById($id))
            return json_encode([ 'message' => 'The product does not exist.' ]);

        if (UserSession::getInstance()->isLoggedIn())
        {
            $model = UserCart::findById($id);

            if (!$model)
                return json_encode([ 'message' => 'You cannot update a product that is not in your cart.' ]);

            $data = $_GET['op'] ?? $_POST['quantity'] ?? false;
            if ($data && $model instanceof UserCart)
            {
                if ($data === 'up' || $data === 'down')
                {
                    $model->quantity += $data === 'up' ? 1 : -1;
                }

                else
                {
                    $model->quantity = (int) $_POST['quantity'];
                }

                if ($model->quantity > 0 && $model->quantity < 1000)
                    $model->save();
            }
        }

        else
        {
            $model = new BrowserCart($_COOKIE['items'] ?? []);

            $data = $_GET['op'] ?? $_POST['quantity'] ?? false;
            if ($data && $data === 'up' || $data === 'down')
            {
                $model->addItem($id, $data === 'up' ? 1 : -1);
            }

            else if ($data && $data > 0)
            {
                $model->updateItem($id, $data);
            }
        }

        return json_encode([
            'redirect' => 'redirect'
        ]);
    }

    public function actionDelete(int $id): string
    {
        header('Content-type: application/json');

        if (!$this->getIsAjax())
            return json_encode([ 'message' => 'This action only supports XMLHttpRequest.' ]);

        if ($id < 1 || !Product::findById($id))
            return json_encode([ 'message' => 'The product does not exist.' ]);

        if (UserSession::getInstance()->isLoggedIn())
        {
            $model = UserCart::findById($id);

            if (!$model)
                return json_encode([ 'message' => 'The product is not in your cart.' ]);

            if ($model instanceof UserCart)
                $model->deleteItem();
        }

        else
        {
            $model = new BrowserCart($_COOKIE['items'] ?? []);
            $model->deleteItem($id);
        }

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
        return $this->render('thanks');
    }
}
