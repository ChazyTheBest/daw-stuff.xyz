<?php

namespace models;

use framework\ActiveRecord;

/**
 * This is the model class for table "user_info".
 *
 * @property string $name
 * @property string $surname
 * @property string $address_1
 * @property string $address_2
 * @property string $city
 * @property string $postal_code
 * @property string $phone
 * @property string $nin
 * @property int $user_id
 */
final class UserInfo extends ActiveRecord
{
    public ?string $name = null;
    public ?string $surname = null;
    public ?string $address_1 = null;
    public ?string $address_2 = null;
    public ?string $city = null;
    public ?string $postal_code = null;
    public ?string $phone = null;
    public ?string $nin = null;
    public int $user_id;


    public static function tableName(): string
    {
        return 'user_info';
    }

    public static function updateCond(): array
    {
        return [ 'user_info', [ 'user_id' ] ];
    }

    public static function findById(int $id): ActiveRecord
    {
        return self::findOne([ 'user_id' => $id ]);
    }
}
