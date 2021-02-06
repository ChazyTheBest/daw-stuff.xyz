<?php

namespace controllers;

use Exception;
use framework\App;
use framework\UserSession;
use models\BrowserCart;
use models\Order;
use models\OrderForm;
use models\OrderLine;
use models\UserCart;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct('order');
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
                        'actions' => [ 'create' ],
                        'roles' => [ '@', '?' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'index' ],
                        'roles' => [ '@' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'view' ],
                        'roles' => [ 'client', 'staff', 'admin' ],
                        'roleCheck' => [ Order::class, 'findById' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'update', 'delete' ],
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
        return $this->render('index', [
            'model' => new Order()
        ]);
    }

    /**
     * Displays order details.
     *
     * @param int $id
     * @return mixed
     */
    public function actionView(int $id): string
    {
        $model = Order::findById($id);

        if (!$model)
            return $this->render('error');

        return $this->render('view', [
            'model' => $model,
            'lines' => new OrderLine()
        ]);
    }

    /**
     * Displays the create page and creates a new order.
     *
     * @return mixed
     * @throws Exception
     */
    public function actionCreate(): string
    {
        if (!UserSession::getInstance()->isLoggedIn())
            return header('Location: /shoppingCart/signup'); // todo find a more elegant way

        $model = new OrderForm();

        if ($this->getIsAjax())
        {
            if (!$model->load($_POST) || !$model->payment)
                return $this->redirect('/order/error');

            $cart = new UserCart();
            $data = $cart->getCartItems();
            $cartItems = $data['items'];
            $cartProducts = $data['products'];
            $total = 0;

            foreach ($cartProducts as $product)
            {
                $total += ($product['price'] * $cartItems[$product['id']]);
            }

            $order = new Order();
            $order->reference = $model->generateReference();

            // make sure the reference is unique
            while (Order::findByReference($order->reference))
            {
                $order->reference = $model->generateReference();
            }

            $order->total = $total;
            $order->payment = $model->payment;
            $order->status = Order::STATUS_AWAITING;
            $order->created_at = time();
            $order->created_by = App::$user->id;

            if (!$order->save() || !$order->id)
                return $this->redirect('/order/error3');

            foreach ($cartProducts as $product)
            {
                $line = new OrderLine();
                $line->product_id = $product['id'];
                $line->quantity = $cartItems[$product['id']];
                $line->price = $product['price'] * $line->quantity;
                $line->order_id = $order->id;
                if (!$line->save())
                    return $this->redirect('/order/error2');
            }

            $cart->emptyCart();

            return $this->redirect('/shoppingCart/thanks');
        }

        return $this->render('create', [
            'model' => $model,
            'cart' => new BrowserCart($_COOKIE['items'] ?? [])
        ]);
    }
}
