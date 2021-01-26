<?php

namespace models;

use Exception;
use framework\App;

class SignupForm extends Model
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
            [ [ 'name', 'surname', 'address_1', 'city' ], 'required', 'on' => self::SCENARIO_CART ],
            [ 'email', 'trim' ],
            [ [ 'name', 'surname', 'address_1', 'address_2', 'city' ], 'trim', 'on' => self::SCENARIO_CART ],
            [ [ 'email', 'password' ], 'string', 'max' => 255 ],
            [ 'password', 'string', 'min' => 6 ],
            [ [ 'name', 'surname', 'address_1', 'address_2', 'city' ], 'string', 'max' => 50, 'on' => self::SCENARIO_CART ],
            [ 'email', 'email' ],
            [ 'email', 'unique', 'targetClass' => '\models\User' ]
        ];
    }

    public function attributeLabels(): array
    {
        $labels = [
            'email' => App::t('form', 'l_username'),
            'password' => App::t('form', 'l_pwd'),
            'confirm_password' => App::t('form', 'l_cnfpwd')
        ];

        if ($this->scenario === self::SCENARIO_CART)
        {
            $labels['name'] = App::t('form', 'l_name');
            $labels['surname'] = App::t('form', 'l_surname');
            $labels['address_1'] = App::t('form', 'l_address_1');
            $labels['address_2'] = App::t('form', 'l_address_2');
            $labels['city'] = App::t('form', 'l_city');
            $labels['phone'] = App::t('form', 'l_phone');
            $labels['nin'] = App::t('form', 'l_nin');
        }

        return $labels;
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     * @throws Exception
     */
    public function signup(): bool
    {
        if (!($user = $this->createUser()))
            return false;

        $info = new UserInfo();
        $info->user_id = $user->id;

        return $info->save();
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
        if ($this->scenario === self::SCENARIO_CART)
            (new LoginForm())->loginWithoutPassword($user->id);

        return true;
    }

    private function createUser(): ?User
    {
        // validate user input
        if (!$this->validate())
            return null;

        // create a new user
        $user = new User();
        $user->email = $this->email;
        $user->role = 'client';
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