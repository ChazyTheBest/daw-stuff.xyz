<?php

namespace controllers;

use framework\App;
use models\Product;
use models\ProductForm;

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
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'create', 'manage', 'update', 'delete' ], // CRUD
                        'roles' => [ 'admin', 'staff' ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Displays the product presentation page.
     *
     * @param int|null $id
     * @return mixed
     */
    public function actionIndex(?int $id): string
    {
        $model = Product::findById($id);
        if (!$model)
            return $this->error('404', [
                'message' => App::t('error', '404_product')
            ]);

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionCreate(): string
    {
        $model = new ProductForm();

        if ($this->getIsAjax())
        {
            if (!isset($_FILES['images']) || !$model->load($_POST))
                return $this->inform('upload error');

            return $model->create($_FILES['images'])
                ? $this->redirect('/product/manage')
                : $this->inform(App::t('error', 'create_failed'));
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
                'query' => Product::getList(),
                'texts' => [
                    'title' => [
                        'view' => App::t('table', 'td_view_product'),
                        'delete' => App::t('table', 'td_delete_product')
                    ],
                    'buttons' => [
                        'names' => [ 'create' => App::t('table', 'btn_create') ],
                        'create' => [ 'url' => '/product/create' ]
                    ]
                ],
                'lang' => ''
            ]);
        }

        return $this->render('manage');
    }

    public function actionUpdate(?int $id): string
    {
        $product = Product::findById($id);
        if (!$product || !$product instanceof Product)
            return $this->error('404', [
                'message' => App::t('error', '404_product')
            ]);

        $model = new ProductForm();

        if ($this->getIsAjax())
        {
            if (isset($_FILES['images']))
                return $model->updateImage($_FILES['images'], $product)
                    ? $this->redirect('/product/manage')
                    : $this->inform(App::t('error', 'upload_failed'));

            if ($model->load(($_POST)))
                return $model->update($product)
                    ? $this->redirect('/product/manage')
                    : $this->inform(App::t('error', 'update_failed'));
        }

        return $this->render('update', [
            'model' => $model,
            'product' => $product
        ]);
    }

    public function actionDelete(?int $id): string
    {
        if (!$this->getIsAjax())
            return $this->error('405', [
                'message' => App::t('error', '405_ajax')
            ]);

        $model = Product::findById($id);
        if (!$model)
            return $this->inform(App::t('error', '404_product'));

        $model->disable([ 'id' => $id ]);

        return $this->redirect('reload');
    }
}
