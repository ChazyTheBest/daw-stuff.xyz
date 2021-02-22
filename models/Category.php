<?php

namespace models;

use framework\ActiveRecord;
use framework\App;

class Category extends ActiveRecord
{
    public int $id;
    public ?string $name = null;
    public ?string $image = null;
    public ?string $description = null;
    public ?int $category_id = null;
    public ?int $status = null;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;


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
     * @return ActiveRecord|null
     */
    public static function findById(int $id): ?ActiveRecord
    {
        return parent::findOne([
            'id' => $id
        ]);
    }

    /**
     * Get all categories
     *
     * @param int|null $id
     * @return array
     */
    public static function getAll(int $id = null): array
    {
        return self::findAll([
            'category_id' => $id,
            'status' => Category::STATUS_ACTIVE
        ]);
    }

    public static function getList(): array
    {
        $cat = App::t('app', 'category');
        $sub = App::t('app', 'subcategory');

        return (new Category())->custom([
            'select' => [ '`id`', '`name`', '`image`', "IF(`category_id` IS NULL, '$cat', '$sub') as type", '`status`' ]
        ]);
    }
}
