<?php

namespace controllers;

use models\Product;

class ProductController extends Controller
{
    /**
     * SiteController constructor.
     */
    public function __construct()
    {
        parent::__construct('product');
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
     * @param int|null $id
     * @return mixed
     */
    public function actionIndex(?int $id): string
    {
        $model = Product::findById($id);

        if (!$model)
            return $this->render('error');

        return $this->render('index', [
            'model' => $model
        ]);
    }
}
