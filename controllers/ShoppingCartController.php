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
            'model' => UserSession::getInstance()->isLoggedIn()
                ? new UserCart()
                : new BrowserCart($_COOKIE['items'] ?? [])
        ]);
    }

    public function actionAdd(?int $id): string
    {
        if (!$this->getIsAjax())
            return $this->error('405', [
                'message' => App::t('error', '405_ajax')
            ]);

        if ($id < 1 || !Product::findById($id))
            return $this->inform(App::t('error', '404_product'));

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

            else if ($model instanceof UserCart)
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

        return $this->redirect('reload');
    }

    public function actionUpdate(?int $id): string
    {
        if (!$this->getIsAjax())
            return $this->error('405', [
                'message' => App::t('error', '405_ajax')
            ]);

        if ($id < 1 || !Product::findById($id))
            return $this->inform(App::t('error', '404_product'));

        if (UserSession::getInstance()->isLoggedIn())
        {
            $model = UserCart::findById($id);
            if (!$model && !$model instanceof UserCart)
                return json_encode([ 'message' => App::t('error', 'cart_not_found') ]);

            $data = $_GET['op'] ?? $_POST['quantity'] ?? false;
            if ($data)
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

        return $this->redirect('reload');
    }

    public function actionDelete(?int $id): string
    {
        if (!$this->getIsAjax())
            return $this->error('405', [
                'message' => App::t('error', '405_ajax')
            ]);

        if ($id < 1 || !Product::findById($id))
            return $this->inform(App::t('error', '404_product'));

        if (UserSession::getInstance()->isLoggedIn())
        {
            $model = UserCart::findById($id);
            if (!$model && !$model instanceof UserCart)
                return $this->inform(App::t('error', 'cart_not_found'));

            $model->deleteItem();
        }

        else
        {
            $model = new BrowserCart($_COOKIE['items'] ?? []);
            $model->deleteItem($id);
        }

        return $this->redirect('reload');
    }

    public function actionSignup(): string
    {
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_CART;
        if ($model->load($_POST))
            return $model->signupWithInfo()
                ? $this->redirect('/order/create')
                : $this->inform(App::t('error', 'signup_failed'));

        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionThanks(): string
    {
        return $this->render('thanks');
    }
}
