<?php

namespace models;

use framework\App;

class CategoryForm extends FormModel
{
    public ?string $name = null;
    public ?string $description = null;
    public ?string $category_id = null;


    public function rules(): array
    {
        return [
            [ 'name', 'required' ],
            [ [ 'name', 'description' ], 'string', 'max' => 100 ],
            [ 'category_id', 'int', 'check' => function(int $id) { return Category::findById($id); } ]
        ];
    }

    public function attributeLabels(): array
    {
        if ($this->attributeLabels === [])
        {
            $this->attributeLabels = [
                'name' => App::t('form', 'l_name'),
                'image' => App::t('form', 'l_image'),
                'description' => App::t('form', 'l_description'),
                'category_id' => App::t('form', 'l_subcategory')
            ];
        }

        return $this->attributeLabels;
    }

    public function attributeLabel(string $key): string
    {
        return $this->attributeLabels()[$key];
    }

    public function create(array $files): bool
    {
        $this->validate();

        $model = new Category();
        $model->name = $this->name;
        $model->image = 'main.jpg';
        $model->description = $this->description;
        $model->category_id = $this->category_id ?: null;
        $model->status = Category::STATUS_ACTIVE;

        return $model->save() && $this->processImage($files, $model->id);
    }

    public function update(Category $category): bool
    {
        $this->validate();

        $category->name = $this->name;
        $category->description = $this->description;
        $category->category_id = $this->category_id ?: null;

        return $category->save();
    }

    public function updateImage(array $files, Category $category): bool
    {
        if (!$this->processImage($files, $category->id))
            return false;

        return $category->save();
    }

    private function processImage(array $files, int $id): bool
    {
        $rules = App::$config['files']['images'];
        $file = new File($rules['path'] . 'categories/' . $id, $files, $rules);

        return !$file->checkPath() ?? $file->processImage();
    }
}
