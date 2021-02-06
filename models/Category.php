<?php

namespace models;

use framework\ActiveRecord;

class Category extends ActiveRecord
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

    public static function updateCond(): array
    {
        return [ 'category', [ 'id' ] ];
    }

    /**
     * Finds category by id
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

    /**
     * Get all categories
     *
     * @return array
     */
    public static function getAll(): array
    {
        return parent::findAll([]);
    }
}
