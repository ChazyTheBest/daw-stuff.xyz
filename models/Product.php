<?php

namespace models;

use framework\ActiveRecord;

class Product extends ActiveRecord
{
    public int $id;
    public ?string $name = null;
    public ?string $image = null;
    public ?string $description = null;
    public ?float $price = null;
    public ?float $discount = null;
    public ?int $category_id = null;
    public ?int $subcategory_id = null;
    public ?int $status = null;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;


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
     * @param int $cat_id the category id
     * @param int $page the page number (0 to disable)
     * @return array product list with optional pagination
     */
    public static function findByCategory(int $cat_id, int $page = 0): array
    {
        return parent::findAll([
            'category_id' => $cat_id,
            'status' => Product::STATUS_ACTIVE
        ], $page);
    }

    /**
     * Finds products by subcategory
     *
     * @param int $cat_id the category id
     * @param int $sub_id the subcategory id
     * @param int $page the page number (0 to disable)
     * @return array product list with optional pagination
     */
    public static function findBySubcategory(int $cat_id, int $sub_id, int $page = 0): array
    {
        return parent::findAll([
            'category_id' => $cat_id,
            'subcategory_id' => $sub_id,
            'status' => Product::STATUS_ACTIVE
        ], $page);
    }

    /**
     * Finds products by id
     *
     * @param array $id
     * @return array
     */
    public static function findManyById(array $id): array
    {
        return parent::findAll([
            'id' => $id,
            'status' => Product::STATUS_ACTIVE
        ]);
    }

    /**
     * Finds product by id
     *
     * @param int $id
     * @return ActiveRecord|null
     */
    public static function findById(int $id): ?ActiveRecord
    {
        return parent::findOne([
            'id' => $id
        ]);
    }

    public static function getAll(): array
    {
        return self::findAll();
    }
}
