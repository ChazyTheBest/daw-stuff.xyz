<?php

namespace models;

use framework\App;

class UserInfoForm extends FormModel
{
    public ?string $name = null;
    public ?string $surname = null;
    public ?string $address_1 = null;
    public ?string $address_2 = null;
    public ?string $city = null;
    public ?string $phone = null;
    public ?string $nin = null;


    public function rules(): array
    {
        return [
            [ [ 'name', 'surname', 'address_1', 'city' ], 'required' ],
            [ [ 'name', 'surname', 'address_1', 'address_2' ], 'string', 'max' => 100 ],
            [ [ 'city', 'phone', 'nin'], 'string', 'max' => 30 ]
        ];
    }

    public function attributeLabels(): array
    {
        if ($this->attributeLabels === [])
        {
            $this->attributeLabels = [
                'name' => App::t('form', 'l_name'),
                'surname' => App::t('form', 'l_surname'),
                'address_1' => App::t('form', 'l_address_1'),
                'address_2' => App::t('form', 'l_address_2'),
                'city' => App::t('form', 'l_city'),
                'phone' => App::t('form', 'l_phone'),
                'nin' => App::t('form', 'l_nin')
            ];
        }

        return $this->attributeLabels;
    }

    public function attributeLabel(string $key): string
    {
        return $this->attributeLabels()[$key];
    }

    public function update(int $id = null): bool
    {
        $this->validate();

        $model = UserInfo::findById($id ?? App::$user->id);
        $model->name = $this->name;
        $model->surname = $this->surname;
        $model->address_1 = $this->address_1;
        $model->address_2 = $this->address_2;
        $model->city = $this->city;
        $model->phone = $this->phone;
        $model->nin = $this->nin;
        $model->user_id = App::$user->id;

        return $model->save();
    }
}
