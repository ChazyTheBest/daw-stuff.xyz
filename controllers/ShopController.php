<?php

namespace controllers;

use models\Cart;

final class ShopController extends Controller
{
    /**
     * SiteController constructor.
     */
    public function __construct()
    {
        parent::__construct('shop');
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
                        'actions' => [ 'index' ],
                        'roles' => [ '?', '@' ]
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
            'model' => new Cart()
        ]);
    }
}
