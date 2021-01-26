<?php

namespace models;

class Category extends \framework\ActiveRecord
{
    public int $id;
    public string $name;
    public string $image;
    public string $description;


    /**
     * @return string the table name.
     */
    public static function tableName(): string
    {
        return 'category';
    }

    /**
     * Finds category by id
     *
     * @param int $id
     * @return static|null
     */
    public static function findById(int $id): ?Category
    {
        return parent::findOne([
            'id' => $id
        ]);
    }

    /**
     * Get all categories
     *
     * @return static|null
     */
    public static function getAll()
    {
        return parent::findAll([]);
    }
}
