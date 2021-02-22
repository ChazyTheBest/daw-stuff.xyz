<?php

namespace controllers;

use Exception;
use framework\App;
use models\Category;
use models\CategoryForm;

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct('category');
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
                        'actions' => [ 'create', 'manage', 'update', 'delete' ], // CRUD
                        'roles' => [ 'admin', 'staff' ]
                    ]
                ]
            ]
        ];
    }

    public function actionCreate(): string
    {
        $model = new CategoryForm();

        if ($this->getIsAjax())
        {
            if (!isset($_FILES['images']))
                return $this->inform(App::t('error', 'form_images'));

            try
            {
                $model->load($_POST);

                return $model->create($_FILES['images'])
                    ? $this->redirect('/category/manage')
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

            return json_encode([
                'query' => Category::getList(),
                'texts' => [
                    'title' => [
                        'view' => App::t('table', 'td_view_category'),
                        'delete' => App::t('table', 'td_delete_category')
                    ],
                    'buttons' => [
                        'names' => [
                            'create' => App::t('table', 'btn_create'),
                            'reset' => App::t('table', 'btn_all'),
                            'filter' => [
                                App::t('table', 'btn_cat'),
                                App::t('table', 'btn_sub')
                            ]
                        ],
                        'create' => [ 'url' => '/category/create' ],
                        'reset' => [ 'columns' => [ 3 ] ],
                        'filter' => [
                            [
                                'columns' => [ 3 ],
                                'term' => App::t('table', 'btn_cat')
                            ],
                            [
                                'columns' => [ 3 ],
                                'term' => App::t('table', 'btn_sub')
                            ]
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
        $category = Category::findById($id);
        if (!$category || !$category instanceof Category)
            return $this->error('404', [
                'message' => App::t('error', '404_category')
            ]);

        $model = new CategoryForm();

        if ($this->getIsAjax())
        {
            if (isset($_FILES['images']))
                return $model->updateImage($_FILES['images'], $category)
                    ? $this->redirect('/category/manage')
                    : $this->inform(App::t('error', 'upload_failed'));

            else
            {
                try
                {
                    $model->load($_POST);

                    return $model->update($category)
                        ? $this->redirect('/category/manage')
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
            'category' => $category
        ]);
    }

    public function actionDelete(?int $id): string
    {
        if (!$this->getIsAjax())
            return $this->error('405', [
                'message' => App::t('error', '405_ajax')
            ]);

        $model = Category::findById($id);
        if (!$model)
            return $this->inform(App::t('error', '404_category'));

        $model->disable([ 'id' => $id ]);

        return $this->redirect('reload');
    }
}
