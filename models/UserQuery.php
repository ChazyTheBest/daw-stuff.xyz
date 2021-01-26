<?php

namespace models;

use framework\ActiveQuery;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends ActiveQuery
{
    public function active(): array
    {
        return $this->where([ 'status' => User::STATUS_ACTIVE ]);
    }
}
