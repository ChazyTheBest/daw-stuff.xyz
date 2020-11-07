<?php

namespace models;

final class LoginForm extends Model
{
    private string $username;
    private string $password;


    /**
     * LoginForm constructor.
     */
    public function __construct()
    {
        parent::__construct($this);
    }

    public function login(): bool
    {
        $user_list = require dirname(__DIR__) . '/config/user_list.php';

        if (isset($user_list[$this->username]) && $user_list[$this->username] === $this->password)
        {
            $this->loginSuccessful(array_search($this->username, $user_list));

            return true;
        }

        return false;
    }

    private function loginSuccessful(int $id): void
    {
        $_SESSION['user_id'] = $id;
        $_SESSION['IS_LOGGED_IN'] = TRUE;
        $_SESSION['username'] = $this->username;
    }

    // TODO: move this to Model
    public function getFormFields(): string
    {
        $fields = '';

        foreach(get_class_vars(get_class($this)) as $key => $value)
        {
            $fields .= '<label for="' . $key . '">' . ucfirst($key) . '</label>' . "\n";
            $fields .= '<input id="' . $key . '" type="' . ($key === 'password' ? $key : 'text') . '" name="' . explode('\\', get_class($this))[1] . "[$key]" . '">' . "\n";
        }

        $fields .= '<input type="submit" value="Login">' . "\n";

        return $fields;
    }

    // TODO: move this to Model
    public function populate(array $data): bool
    {
        foreach(get_class_vars(get_class($this)) as $key => $value)
        {
            if (!isset($data[$key]))
                return false;

            $this->$key = $data[$key];
        }

        return true;
    }
}
