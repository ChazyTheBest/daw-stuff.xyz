<?php

namespace models;

class Product extends \framework\ActiveRecord
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

    /**
     * Finds products by category
     *
     * @param int $cat_id
     * @param int $page
     * @return static|null
     */
    public static function findByCategory(int $cat_id, int $page)
    {
        return parent::findAll([
            'category_id' => $cat_id
        ], $page);
    }

    /**
     * Finds products by id
     *
     * @param array $id
     * @return static|null
     */
    public static function findManyById(array $id)
    {
        return parent::findAll([
            'id' => $id
        ]);
    }

    /**
     * Finds product by id
     *
     * @param int $id
     * @return static|null
     */
    public static function findById(int $id): ?Product
    {
        return parent::findOne([
            'id' => $id
        ]);
    }
}
