<?php

namespace models;

use framework\App;

class ProductForm extends Model
{
    public ?string $name = null;
    public ?string $description = null;
    public ?string $price = null;
    public ?string $discount = null;
    public ?string $category = null;
    public ?string $subcategory = null;


    public function rules(): array
    {
        return [
            [ [ 'name', 'price' ], 'required' ],
            [ [ 'name', 'description' ], 'string', 'max' => 100 ],
            [ 'price', 'decimal', [ 8,2 ] ],
            [ 'discount', 'decimal', [ 4,3 ] ],
            [ 'category', 'int', 'check' => function(int $id) { return Category::findById($id); } ],
            [ 'subcategory', 'int', 'check' => function(int $id) { return Category::findById($id); } ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => App::t('form', 'l_name'),
            'description' => App::t('form', 'l_description'),
            'image' => App::t('form', 'l_image'),
            'price' => App::t('form', 'l_price'),
            'discount' => App::t('form', 'l_discount'),
            'category' => App::t('form', 'l_category'),
            'subcategory' => App::t('form', 'l_subcategory')
        ];
    }

    public function create(array $files): bool
    {
        if (!$this->validate())
            return false;

        $model = new Product();
        $model->name = $this->name;
        $model->description = $this->description;
        $model->image = 'main.jpg';
        $model->price = $this->price;
        $model->discount = $this->discount ?: 0;
        $model->category_id = $this->category ?: null;
        $model->subcategory_id = $this->subcategory ?: null;
        $model->status = Product::STATUS_ACTIVE;

        return $model->save() && $this->processImage($files, $model->id);
    }

    public function update(Product $product): bool
    {
        if (!$this->validate())
            return false;

        $product->name = $this->name;
        $product->description = $this->description;
        $product->price = $this->price;
        $product->discount = $this->discount ?: 0;
        $product->category_id = $this->category ?: null;
        $product->subcategory_id = $this->subcategory ?: null;

        return $product->save();
    }

    public function updateImage(array $files, Product $product): bool
    {
        if (!$this->processImage($files, $product->id))
            return false;

        return $product->save();
    }

    private function processImage(array $files, int $id): bool
    {
        $rules = App::$config['files']['images'];
        $file = new File($rules['path'] . 'products/' . $id, $files, $rules);

        return !$file->checkPath() ?? $file->processImage();
    }
}
