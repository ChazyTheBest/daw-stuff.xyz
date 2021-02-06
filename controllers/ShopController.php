<?php

namespace controllers;

use models\Category;
use models\Product;

final class ShopController extends Controller
{
    /**
     * SiteController constructor.
     */
    public function __construct()
    {
        parent::__construct('shop');
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
     * @param int|null $cat
     * @return mixed
     */
    public function actionIndex(?int $cat): string
    {
        $cats = Category::getAll();
        $cat = $cat ?? $cats[0]['id'];
        $page = $_GET['page'] ?? 1;

        return $this->render('index', [
            'cats' => $cats,
            'pagination' => Product::findByCategory($cat, $page)
        ]);
    }
}
