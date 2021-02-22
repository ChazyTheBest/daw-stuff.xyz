<?php

namespace controllers;

use Exception;
use framework\App;
use models\Category;
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
            if (!isset($_FILES['images']))
                return $this->inform(App::t('error', 'form_images'));

            try
            {
                $model->load($_POST);

                $model->scenario = ProductForm::SCENARIO_NEW;

                return $model->create($_FILES['images'])
                    ? $this->redirect('/product/manage')
                    : $this->inform(App::t('error', 'create_failed'));
            }

            catch (Exception $e)
            {
                return $this->inform($e->getMessage());
            }
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

            $categories = [];

            foreach (Category::getAll() as $cat)
            {
                $categories[$cat['id']] = [
                    'name' => $cat['name'],
                    'subcategories' => []
                ];

                foreach (Category::getAll($cat['id']) as $sub)
                {
                    $categories[$cat['id']]['subcategories'][] = [
                        'id' => $sub['id'],
                        'name' => $sub['name']
                    ];
                }
            }

            if (isset($_GET['cat']))
            {
                $data = [
                    'subcategories' => []
                ];
            }

            return json_encode([
                'query' => Product::getAll(),
                'texts' => [
                    'title' => [
                        'view' => App::t('table', 'td_view_product'),
                        'delete' => App::t('table', 'td_delete_product')
                    ],
                    'categories' => $categories,
                    'none' => App::t('table', 'td_none'),
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
            // update product images
            if (isset($_FILES['images']))
                return $model->updateImage($_FILES['images'], $product)
                    ? $this->redirect('/product/manage')
                    : $this->inform(App::t('error', 'upload_failed'));

            // enable/disable product
            else if (isset($_GET['status']))
            {
                $status = $_GET['status'];
                if ($status === 'enable' && $product->status === Product::STATUS_DELETED)
                {
                    // enable
                    $product->enable();
                }

                else if ($status === 'disable' && $product->status === Product::STATUS_ACTIVE)
                {
                    //disable
                    $product->disable();
                }

                return $this->redirect('reload');
            }

            // change category and get subcategories
            else if (isset($_GET['cat']))
            {
                $subcategories = [];

                $cat_id = $_GET['cat'];
                if ($cat_id !== 'null')
                {
                    $category = Category::findById($cat_id);
                    if (!$category)
                        return $this->inform(App::t('error', '404_category'));

                    foreach (Category::getAll($cat_id) as $sub)
                    {
                        $subcategories[$sub['id']] = [ $sub['name'] ];
                    }
                }

                $product->category_id = $cat_id === 'null' ? null : (int) $cat_id;
                $product->save();

                header('Content-type: application/json');
                return json_encode([
                    'message' => App::t('table', 'product_cat'),
                    'subcategories' => $subcategories
                ]);
            }

            // change subcategory
            else if (isset($_GET['sub']))
            {
                $sub_id = $_GET['sub'];
                if ($sub_id !== 'null')
                {
                    $subcategory = Category::findById($sub_id);
                    if (!$subcategory)
                        return $this->inform(App::t('error', '404_category'));
                }

                $product->subcategory_id = $sub_id === 'null' ? null : (int) $sub_id;
                $product->save();

                return $this->inform(App::t('table', 'product_sub'));
            }

            else
            {
                try
                {
                    $model->load($_POST);

                    return $model->update($product)
                        ? $this->redirect('/product/manage')
                        : $this->inform(App::t('error', 'update_failed'));
                }

                catch (Exception $e)
                {
                    return $this->inform($e->getMessage());
                }
            }
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

        $model->disable();

        return $this->redirect('reload');
    }
}
