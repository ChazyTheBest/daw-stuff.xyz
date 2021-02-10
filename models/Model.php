<?php

namespace models;

use framework\App;

abstract class Model
{
    protected string $formName;
    public int $scenario = -1;

    public function __construct()
    {
        $this->formName = explode('\\', get_class($this))[1];
    }

    public function load(array $data): bool
    {
        if (isset($data[$this->formName]))
            return $this->populate($data[$this->formName]);

        return false;
    }

    private function populate(array $data): bool
    {
        foreach($this->attributeLabels() as $key => $value)
        {
            if (!isset($data[$key]))
                return false;

            $this->$key = $data[$key];
        }

        return true;
    }

    public function validate(): bool
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

                    if (!$this->checkProp($prop, $rule))
                        return false;
                }
            }

            else
            {
                if (!$this->checkProp($rule[0], $rule))
                    return false;
            }
        }

        return true;
    }

    private function checkScenario(int $scenario): bool
    {
        return $this->scenario === $scenario;
    }

    private function checkProp(string $prop, array $rule): bool
    {
        if (!property_exists($this, $prop))
            return false;

        switch ($rule[1])
        {
            case 'required':
            {
                if ($this->$prop === '' || $this->$prop === null)
                    return false;

                break;
            }
            case 'string':
            {
                if ($this->$prop === '')
                    break;

                if (isset($rule['max']) && mb_strlen($this->$prop) > $rule['max'])
                    return false;

                else if (isset($rule['min']) && mb_strlen($this->$prop) < $rule['min'])
                    return false;

                break;
            }
            case 'int':
            {
                if ($this->$prop === '')
                    break;

                if (!is_numeric($this->$prop) || is_double($this->$prop))
                    return false;

                if (isset($rule['matches']) && !in_array((int)$this->$prop, $rule['matches'], true)) // prevent coercion
                    return false;

                else if (isset($rule['check']) && !$rule['check']($this->$prop))
                    return false;

                break;
            }
            case 'decimal':
            {
                if ($this->$prop === '' || $this->$prop === '0')
                    break;

                if (!is_float($this->$prop + 0))
                    return false;

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
                    return false;

                break;
            }
            case 'unique':
            {
                if ($this->$prop === '')
                    break;

                $methodName = 'findBy' . ucfirst($prop);

                if (!isset($rule['targetClass']) && !$this->checkMethod($rule['targetClass'], $methodName))
                    return false;

                $model = [ $rule['targetClass'], $methodName ]($this->$prop);

                // must be unique       redundant?
                if ($model && $model->$prop === $this->$prop)
                    return false;

                break;
            }
            default:
            {
                if ($this->$prop === '')
                    break;

                // custom validation method
                if ($this->checkMethod($this, $rule[1]) && ![ $this, $rule[1] ]($this->$prop))
                    return false;
            }
        }

        return true;
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
}
