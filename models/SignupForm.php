<?php

namespace models;

use framework\App;

class SignupForm extends FormModel
{
    const SCENARIO_CART = 0;

    public string

            $email,
            $password,
            $name,
            $surname,
            $address_1,
            $address_2,
            $city,
            $phone,
            $nin;


    public function rules(): array
    {
        return [
            [ [ 'email', 'password' ], 'required' ],
            [ [ 'email', 'password' ], 'string', 'max' => 255 ],
            [ 'email', 'trim' ],
            [ 'email', 'email' ],
            [ 'email', 'unique', 'targetClass' => '\models\User' ],
            [ 'password', 'string', 'min' => 6 ],
            [ [ 'name', 'surname', 'address_1', 'city' ], 'required', 'on' => self::SCENARIO_CART ],
            [ [ 'name', 'surname', 'address_1', 'address_2', 'city' ], 'trim', 'on' => self::SCENARIO_CART ],
            [ [ 'name', 'surname', 'address_1', 'address_2', 'city' ], 'string', 'max' => 50, 'on' => self::SCENARIO_CART ]
        ];
    }

    public function attributeLabels(): array
    {
        if ($this->attributeLabels === [])
        {
            $this->attributeLabels = [
                'email' => App::t('form', 'l_username'),
                'password' => App::t('form', 'l_pwd'),
                'confirm_password' => App::t('form', 'l_cnfpwd')
            ];

            if ($this->scenario === self::SCENARIO_CART)
            {
                $this->attributeLabels['name'] = App::t('form', 'l_name');
                $this->attributeLabels['surname'] = App::t('form', 'l_surname');
                $this->attributeLabels['address_1'] = App::t('form', 'l_address_1');
                $this->attributeLabels['address_2'] = App::t('form', 'l_address_2');
                $this->attributeLabels['city'] = App::t('form', 'l_city');
                $this->attributeLabels['phone'] = App::t('form', 'l_phone');
                $this->attributeLabels['nin'] = App::t('form', 'l_nin');
            }
        }

        return $this->attributeLabels;
    }

    public function attributeLabel(string $key): string
    {
        return $this->attributeLabels()[$key];
    }

    /**
     * Signs user up.
     *
     * @param string $role
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup(string $role = 'customer'): bool
    {
        if (!($user = $this->createUser($role)))
            return false;

        $info = new UserInfo();
        $info->name = '';
        $info->surname = '';
        $info->address_1 = '';
        $info->address_2 = '';
        $info->city = '';
        $info->phone = '';
        $info->nin = '';
        $info->user_id = $user->id;

        if (!$info->save())
            return false;

        // login the user
        (new LoginForm())->loginWithoutPassword($user);

        return true;
    }

    public function signupWithInfo(): bool
    {
        if (!($user = $this->createUser()))
            return false;

        $info = new UserInfo();
        $info->name = $this->name;
        $info->surname = $this->surname;
        $info->address_1 = $this->address_1;
        $info->address_2 = $this->address_2;
        $info->city = $this->city;
        $info->phone = $this->phone;
        $info->nin = $this->nin;
        $info->user_id = $user->id;

        if (!$info->save())
            return false;

        // login the user
        (new LoginForm())->loginWithoutPassword($user);

        return true;
    }

    private function createUser(string $role = 'customer'): ?User
    {
        // validate user input
        $this->validate();

        if (!in_array($role, [ 'customer', 'staff' ]))
            return null;

        // create a new user
        $user = new User();
        $user->email = $this->email;
        $user->role = $role;
        $user->setPassword($this->password);
        //$user->generateAuthKey();
        //$user->generateEmailVerificationToken();
        $user->created_at = time();

        // active by default
        $user->status = User::STATUS_ACTIVE;

        // insert the new user into the database
        if (!$user->save() || !$user->id)
            return null;

        // send the verification email
        //$this->sendEmail($user)

        return $user;
    }
}
