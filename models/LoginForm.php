<?php

namespace models;

use Exception;
use framework\App;

final class LoginForm extends FormModel
{
    public string $email;
    public string $password;

    private ?User $_user = null;

    public function rules(): array
    {
        return [
            // email and password are both required
            [ [ 'email', 'password' ], 'required' ],
            [ 'email', 'email' ],
            [ 'email', 'checkUser' ],
            // password is validated by validatePassword()
            [ 'password', 'validatePassword' ],
        ];
    }

    public function attributeLabels(): array
    {
        if ($this->attributeLabels === [])
        {
            $this->attributeLabels = [
                'email' => 'Email',
                'password' => App::t('form', 'l_pwd')
            ];
        }

        return $this->attributeLabels;
    }

    public function attributeLabel(string $key): string
    {
        return $this->attributeLabels()[$key];
    }

    protected function checkUser(): bool
    {
        return !$this->getUser() ? false : true;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $password the password currently being validated
     * @return bool whether the user password is valid or not
     * @throws Exception
     */
    protected function validatePassword(string $password): bool
    {
        return $this->getUser()->validatePassword($password);
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        $this->validate();

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

        $user->moveCart();
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if (!$this->_user)
            $this->_user = User::findByEmail($this->email);

        if (!$this->_user instanceof User)
            return null;

        return $this->_user;
    }
}
