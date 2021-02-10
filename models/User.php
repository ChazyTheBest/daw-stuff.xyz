<?php

namespace models;

use framework\ActiveRecord;
use framework\App;

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
     * return ActiveQuery
     */
    public function getUserInfo(): ActiveRecord
    {
        return UserInfo::findById($this->id);
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

    public function moveCart(): void
    {
        // move cart items from cookie to permanent storage
        if (isset($_COOKIE['items']))
        {
            foreach ($_COOKIE['items'] as $id => $quantity)
            {
                if ($id < 1 || !Product::findById($id))
                    continue;

                $model = new UserCart();
                $model->product_id = $id;
                $model->quantity = $quantity ?? 1;
                $model->created_by = $this->id;

                if ($model->quantity > 0 && $model->quantity < 1000)
                    $model->save();
            }

            (new BrowserCart($_COOKIE['items']))->emptyCart();
        }
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return ActiveRecord
     */
    public static function findByEmail(string $email): ?ActiveRecord
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
     * @return ActiveRecord
     */
    public static function findById(int $id): ?ActiveRecord
    {
        return parent::findOne([
            'id' => $id,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    public static function getList(array $roles): array
    {
        return (new User)->custom([
            'select' => [ '`id`', '`email`', '`name`', '`role`', '`status`', '`created_at`' ],
            'innerjoin' => [ '`user_info` ui', 'on' => [ 'ui.`user_id`' => '`id`' ] ],
            'cond' => [ 'role' => $roles ]
        ]);
    }

    public static function getStatusList(): array
    {
        return [
            User::STATUS_DELETED => App::t('table', 'td_status_0'),
            User::STATUS_INACTIVE => App::t('table', 'td_status_9'),
            User::STATUS_ACTIVE => App::t('table', 'td_status_10')
        ];
    }
}
