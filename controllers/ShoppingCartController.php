<?php

namespace controllers;

use models\Cart;

final class ShoppingCartController extends Controller
{
    /**
     * SiteController constructor.
     */
    public function __construct()
    {
        parent::__construct('shopping_cart');
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
                        'actions' => [ 'pay', 'thanks' ],
                        'roles' => [ '@' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'index', 'add', 'remove' ],
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
        return $this->render('index', [
            'model' => new Cart()
        ]);
    }

    public function actionAdd(string $ref): string
    {
        $model = new Cart();
        $model->addItem($ref);

        header('Content-type: application/json');
        return json_encode([
            'quantity' => $model->getItemCount()
        ]);
    }

    public function actionRemove(): string
    {
        return '';
    }

    public function actionPay(): string
    {
        (new Cart())->processCart();

        header('Content-type: application/json');
        return json_encode([
            'redirect' => '/shoppingCart/thanks'
        ]);
    }

    public function actionThanks(): string
    {
        return $this->render('thanks');
    }
}
