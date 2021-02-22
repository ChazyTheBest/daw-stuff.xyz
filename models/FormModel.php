<?php

namespace models;

use Exception;
use framework\App;

abstract class FormModel
{
    protected string $formName;
    protected array $attributeLabels = [];
    public int $scenario = -1;

    public function __construct()
    {
        $this->formName = explode('\\', get_class($this))[1];
    }

    public function load(array $data): void
    {
        if (!isset($data[$this->formName]))
            throw new Exception(App::t('error', 'form_unk_err'));

        $this->populate($data[$this->formName]);
    }

    private function populate(array $data): void
    {
        foreach($this->attributeLabels() as $key => $value)
        {
            if (!isset($data[$key]))
                throw new Exception(App::t('error', 'form_missing_field', [ strtolower($this->attributeLabel($key)) ]));

            $this->$key = $data[$key];
        }
    }

    public function validate(): void
    {
        foreach ($this->rules() as $rule)
        {
            if (is_array($rule[0]))
            {
                foreach ($rule[0] as $prop)
                {
                    if (isset($rule['on']))
                    {
                        if (!$this->checkScenario($rule['on']))
                            continue 2;
                    }

                    $this->checkProp($prop, $rule);
                }
            }

            else
            {
                if (isset($rule['on']))
                {
                    if (!$this->checkScenario($rule['on']))
                        continue 1;
                }

                $this->checkProp($rule[0], $rule);
            }
        }
    }

    private function checkScenario(int $scenario): bool
    {
        return $this->scenario === $scenario;
    }

    private function checkProp(string $prop, array $rule): void
    {
        if (!property_exists($this, $prop))
            throw new Exception(App::t('error', 'form_unk_err'));

        switch ($rule[1])
        {
            case 'required':
            {
                if ($this->$prop === '' || $this->$prop === null)
                    throw new Exception(App::t('error', 'form_required', [ strtolower($this->attributeLabel($prop)) ]));

                break;
            }
            case 'string':
            {
                if ($this->$prop === '')
                    break;

                if (isset($rule['max']) && mb_strlen($this->$prop) > $rule['max'])
                    throw new Exception(App::t('error', 'form_string_max', [ strtolower($this->attributeLabel($prop)) ]));

                else if (isset($rule['min']) && mb_strlen($this->$prop) < $rule['min'])
                    throw new Exception(App::t('error', 'form_string_min', [ strtolower($this->attributeLabel($prop)) ]));

                break;
            }
            case 'int':
            {
                if ($this->$prop === '')
                    break;

                if (!is_numeric($this->$prop) || is_double($this->$prop))
                    throw new Exception(App::t('error', 'form_int', [ strtolower($this->attributeLabel($prop)) ]));

                if (isset($rule['matches']) && !in_array((int)$this->$prop, $rule['matches'], true)) // prevent coercion
                    throw new Exception(App::t('error', 'form_int_match', [ strtolower($this->attributeLabel($prop)) ]));

                else if (isset($rule['check']) && !$rule['check']($this->$prop))
                    throw new Exception(App::t('error', 'form_int_check', [ strtolower($this->attributeLabel($prop)) ]));

                break;
            }
            case 'decimal':
            {
                if ($this->$prop === '' || $this->$prop === '0')
                    break;

                if (!filter_var($this->$prop, FILTER_VALIDATE_FLOAT))
                    throw new Exception(App::t('error', 'form_decimal', [ strtolower($this->attributeLabel($prop)) ]));

                // todo check size and decimal places

                break;
            }
            case 'trim':
            {
                $this->$prop = trim($this->$prop);

                break;
            }
            case 'email':
            {
                if (!filter_var($this->$prop, FILTER_VALIDATE_EMAIL))
                    throw new Exception(App::t('error', 'form_email'));

                break;
            }
            case 'unique':
            {
                if ($this->$prop === '')
                    break;

                $methodName = 'findBy' . ucfirst($prop);

                if (!isset($rule['targetClass']) && !$this->checkMethod($rule['targetClass'], $methodName))
                    throw new Exception(App::t('error', 'form_unk_err'));

                $model = [ $rule['targetClass'], $methodName ]($this->$prop);

                // must be unique       redundant?
                if ($model && $model->$prop === $this->$prop)
                    throw new Exception(App::t('error', 'form_unique', [ strtolower($this->attributeLabel($prop)) ]));

                break;
            }
            default:
            {
                if ($this->$prop === '')
                    break;

                // custom validation method
                if ($this->checkMethod($this, $rule[1]) && ![ $this, $rule[1] ]($this->$prop))
                    throw new Exception(App::t('error', in_array($prop, [ 'email', 'password' ])
                                                                    ? 'form_login'
                                                                    : 'form_valid', [ strtolower($this->attributeLabel($prop)) ]));
            }
        }
    }

    private function checkMethod($obj_or_class, string $name): bool
    {
        return method_exists($obj_or_class, $name) && is_callable([ $obj_or_class, $name ]);
    }

    // TODO: implement FormActive Class
    public function getFormFields(array $call = null, int $id = null): string
    {
        $fields = '';
        $model = !$call ? null : $call($id ?? App::$user->id);

        foreach($this->attributeLabels() as $key => $value)
        {
            $val = $model->$key ?? '';
            $fields .= "<label for=\"$key\">$value</label>\n";
            $fields .= "<input id=\"$key\" type=\"" . ($key === 'password' ? $key : 'text') . "\" placeholder=\"$key\" name=\"$this->formName[$key]\" value='$val'>\n";
        }

        return $fields;
    }

    abstract function rules(): array;
    abstract public function attributeLabels(): array;
    abstract public function attributeLabel(string $key): string;
}
