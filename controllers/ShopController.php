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
                        'actions' => [ 'index', 'search' ],
                        'roles' => [ '?', '@' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'create', 'read', 'update', 'delete' ], // CRUD
                        'roles' => [ 'admin', 'staff' ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @param int|null $cat
     * @param int|null $sub
     * @return mixed
     */
    public function actionIndex(?int $cat, ?int $sub = 0): string
    {
        $cats = Category::getAll();
        $page = $_GET['page'] ?? 1;
        $cat = $cat ?? $cats[0]['id'];

        if ($sub > 0 && $cat > 0)
            $pagination = Product::findBySubcategory($cat, $sub, $page);

        else
            $pagination = Product::findByCategory($cat, $page);

        return $this->render('index', [
            'cats' => $cats,
            'pagination' => $pagination !== [] ? $pagination : [ 'total' => 0, 'page' => $page, 'products' => [] ],
            'cat_id' => $cat,
            'sub_id' => $sub
        ]);
    }

    // TODO search
    public function actionSearch(): string
    {
        return '';
    }
}
