<?php

namespace models;

use framework\ActiveRecord;
use framework\App;

final class LoginForm extends Model
{
    public string $email;
    public string $password;

    private ?User $_user = null;

    public function rules(): array
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => App::t('form', 'l_email'),
            'password' => App::t('form', 'l_pwd')
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $password the password currently being validated
     * @return bool whether the user password is valid or not
     */
    public function validatePassword(string $password): bool
    {
        if (!($user = $this->getUser()))
            return false;

        return $user->validatePassword($password);
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        if (!$this->validate())
            return false;

        $this->loginSuccessful($this->getUser());

        return true;
    }

    public function loginWithoutPassword(User $user)
    {
        $this->loginSuccessful($user);
    }

    private function loginSuccessful(User $user): void
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['IS_LOGGED_IN'] = TRUE;
        //$_SESSION['email'] = $this->email;

        $user->moveCart();
    }

    /**
     * Finds user by [[email]]
     *
     * @return ActiveRecord|null
     */
    protected function getUser(): ?ActiveRecord
    {
        if (!$this->_user)
            $this->_user = User::findByEmail($this->email);

        return $this->_user;
    }
}
