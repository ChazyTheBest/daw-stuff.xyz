<?php

namespace models;

use framework\App;

class UserInfoForm extends Model
{
    public ?string $name = null;
    public ?string $surname = null;
    public ?string $address_1 = null;
    public ?string $address_2 = null;
    public ?string $city = null;
    public ?string $postal_code = null;
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
        return [
            'name' => App::t('form', 'l_name'),
            'surname' => App::t('form', 'l_surname'),
            'address_1' => App::t('form', 'l_address_1'),
            'address_2' => App::t('form', 'l_address_2'),
            'city' => App::t('form', 'l_city'),
            'postal_code' => App::t('form', 'l_postal_code'),
            'phone' => App::t('form', 'l_phone'),
            'nin' => App::t('form', 'l_nin')
        ];
    }

    // TODO: implement FormActive Class
    public function getFormFields(): string
    {
        $fields = '';
        $info = UserInfo::findById(App::$user->id);

        foreach($this->attributeLabels() as $key => $value)
        {
            $fields .= "<label for=\"$key\">$value</label>\n";
            $fields .= "<input id=\"$key\" type=\"text\" placeholder=\"$key\" name=\"$this->formName[$key]\" value='{$info->$key}'>\n";
        }

        return $fields;
    }

    public function update(): bool
    {
        if (!$this->validate())
            return false;

        $model = UserInfo::findById(App::$user->id);
        $model->name = $this->name;
        $model->surname = $this->surname;
        $model->address_1 = $this->address_1;
        $model->address_2 = $this->address_2;
        $model->city = $this->city;
        $model->postal_code = $this->postal_code;
        $model->phone = $this->phone;
        $model->nin = $this->nin;
        $model->user_id = App::$user->id;

        return $model->save();
    }
}
