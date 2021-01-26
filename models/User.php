<?php

namespace models;

use framework\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $role
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $verification_token
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User[] $users
 * @property UserInfo $userInfo one-to-one relationship: user_info.user_id PK FK (user.id)
 */
final class User extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    // since reflection doesn't support magic properties
    public int $id;
    public string $email;
    public string $role;
    //public string $auth_key;
    public string $password_hash;
    //public string $password_reset_token;
    //public string $verification_token;
    public int $status;
    public int $created_at;
    //public int $updated_at;


    /**
     * @return string the table name.
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * Gets query for [[UserInfo]].
     *
     * return ActiveQuery|UserInfoQuery
     */
    public function getUserInfo()
    {
        //return $this->hasOne(UserInfo::class, [ 'user_id' => 'id' ]);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password_hash = password_hash($password, PASSWORD_ARGON2ID);
    }

    public function validatePassword(string $password): bool
    {
        return password_verify($password, $this->password_hash);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail(string $email): ?User
    {
        return parent::findOne([
            'email' => $email,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * Finds user by id
     *
     * @param int $id
     * @return static|null
     */
    public static function findById(int $id): ?User
    {
        return parent::findOne([
            'id' => $id,
            'status' => self::STATUS_ACTIVE
        ]);
    }
}
