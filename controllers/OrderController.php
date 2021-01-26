<?php

namespace controllers;

use Exception;
use framework\App;
use framework\UserSession;
use models\Cart;
use models\Order;
use models\OrderForm;
use models\OrderLine;

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
                        'actions' => [ 'index', 'view' ],
                        'roles' => [ '@' ]
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
        return $this->render('view', [
            'model' => Order::findById($id),
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
            return header('Location: /shoppingCart/signup');

        if (!isset($_COOKIE['items']))
            return header('Location: /shoppingCart/error');

        $model = new OrderForm();

        if ($this->getIsAjax())
        {
            if (!$model->load($_POST) || !$model->payment)
                return $this->redirect('/order/error');

            $cart = new Cart();
            $cartItems = $cart->getCartItems();
            $total = 0;
            foreach ($cartItems as $item)
            {
                $total += ($item['price'] * $_COOKIE['items'][$item['id']]);
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
                return $this->redirect('/order/error');

            for ($i = 0; $i < count($cartItems); $i++)
            {
                $$i = new OrderLine();
                $$i->product_id = $cartItems[$i]['id'];
                $$i->quantity = $_COOKIE['items'][$cartItems[$i]['id']];
                $$i->price = $cartItems[$i]['price'] * $$i->quantity;
                $$i->order_id = $order->id;
                if (!$$i->save())
                    return $this->redirect('/order/error');
            }

            return $this->redirect('/shoppingCart/thanks');
        }

        return $this->render('create', [
            'model' => $model,
            'cart' => new Cart()
        ]);
    }
}
