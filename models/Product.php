<?php

namespace models;

use framework\ActiveRecord;

class Product extends ActiveRecord
{
    public int $id;
    public string $name;
    public string $image;
    public string $description;
    public float $price;
    public float $discount;
    public int $category_id;


    /**
     * @return string the table name.
     */
    public static function tableName(): string
    {
        return 'product';
    }

    public static function updateCond(): array
    {
        return [ 'product', [ 'id' ] ];
    }

    /**
     * Finds products by category
     *
     * @param int $cat_id
     * @param int $page
     * @return array
     */
    public static function findByCategory(int $cat_id, int $page): array
    {
        return parent::findAll([
            'category_id' => $cat_id
        ], $page);
    }

    /**
     * Finds products by id
     *
     * @param array $id
     * @return array|null
     */
    public static function findManyById(array $id): array
    {
        return parent::findAll([
            'id' => $id
        ]);
    }

    /**
     * Finds product by id
     *
     * @param int $id
     * @return ActiveRecord
     */
    public static function findById(int $id): ActiveRecord
    {
        return parent::findOne([
            'id' => $id
        ]);
    }
}
