<?php

namespace controllers;

use Exception;
use framework\App;
use framework\UserSession;
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
                        'actions' => [ 'view', 'delete' ],
                        'roles' => [ 'customer', 'staff', 'admin' ],
                        'roleCheck' => [ Order::class, 'findById' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'manage', 'update' ],
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
     * @param int|null $id
     * @return mixed
     */
    public function actionView(?int $id): string
    {
        $model = Order::findById($id);
        if (!$model)
            return $this->error('404', [
                'message' => App::t('error', '404_order')
            ]);

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

        if ($this->getIsAjax()) // TODO: implement transactions
        {
            if (!$model->load($_POST))
                return $this->inform(App::t('error', 'model_load'));

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
                return $this->inform(App::t('error', 'model_save'));

            foreach ($cartProducts as $product)
            {
                $line = new OrderLine();
                $line->product_id = $product['id'];
                $line->quantity = $cartItems[$product['id']];
                $line->price = $product['price'] * $line->quantity;
                $line->order_id = $order->id;

                if (!$line->save())
                    return $this->inform(App::t('error', 'model_load'));
            }

            $cart->emptyCart();

            return $this->redirect('/shoppingCart/thanks');
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionManage(): string
    {
        if ($this->getIsAjax())
        {
            header('Content-type: application/json');

            return json_encode([
                'query' => Order::getList(),
                'texts' => [
                    'title' => [
                        'view' => App::t('table', 'td_view_order'),
                        'delete' => App::t('table', 'td_delete_order')
                    ],
                    'status' => Order::getStatusList(),
                    'buttons' => [
                        'names' => [
                            'reset' => App::t('table', 'btn_all'),
                            'filter' => [
                                App::t('table', 'td_status_0'),
                                App::t('table', 'td_status_3'),
                                App::t('table', 'td_status_4'),
                                App::t('table', 'td_status_6')
                            ]
                        ],
                        'reset' => [ 'columns' => [ 3 ] ],
                        'filter' => [
                            [ 'columns' => [ 3 ], 'term' => App::t('table', 'td_status_0') ],
                            [ 'columns' => [ 3 ], 'term' => App::t('table', 'td_status_3') ],
                            [ 'columns' => [ 3 ], 'term' => App::t('table', 'td_status_4') ],
                            [ 'columns' => [ 3 ], 'term' => App::t('table', 'td_status_6') ],
                        ]
                    ]
                ],
                'lang' => ''
            ]);
        }

        return $this->render('manage');
    }

    public function actionUpdate(?int $id): string
    {
        if (!$this->getIsAjax())
            return $this->error('405', [
                'message' => App::t('error', '405_ajax')
            ]);

        $model = Order::findById($id);
        if (!$model && !$model instanceof Order)
            return $this->inform(App::t('error', '404_order'));

        $status = $_POST['status'] ?? null;
        if ($model->checkStatus($status))
            return $this->inform(App::t('error', 'order_invalid_status'));

        $model->setStatus($status);

        return $this->redirect('reload');
    }

    public function actionDelete(?int $id): string
    {
        if (!$this->getIsAjax())
            return $this->error('405', [
                'message' => App::t('error', '405_ajax')
            ]);

        $model = Order::findById($id);
        if (!$model || !$model instanceof Order)
            return $this->inform(App::t('error', '404_order'));

        if (App::$user->role === 'customer' && $model->status > Order::STATUS_DECLINED)
            return $this->inform(App::t('error', 'order_delete_refund'));

        $cond = [ 'id' => $id ];
        if (App::$user->role === 'customer')
            $cond['created_by'] = App::$user->id;

        $model->disable($cond);

        return $this->redirect('reload');
    }
}
