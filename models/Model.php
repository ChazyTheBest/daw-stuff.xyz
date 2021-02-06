<?php

namespace models;

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

                    if (!$this->checkProp($prop, [ $rule[1], $rule['targetClass'] ?? null]))
                        return false;
                }
            }

            else
            {
                if (!$this->checkProp($rule[0], [ $rule[1], $rule['targetClass'] ?? null]))
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

        switch ($rule[0])
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

                if (!is_string($this->$prop))
                    return false;

                if (isset($rule['max']) && $this->$prop > $rule['max'])
                    return false;

                else if (isset($rule['min']) && $this->$prop < $rule['min'])
                    return false;

                break;
            }
            case 'integer':
            {
                if (!is_numeric($this->$prop) || is_double($this->$prop))
                    return false;

                // todo match

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
                $methodName = 'findBy' . ucfirst($prop);

                if (!$rule[1] && !$this->checkMethod($rule[1], $methodName))
                    return false;

                $model = [ $rule[1], $methodName ]($this->$prop);

                // must be unique
                if ($model && $model->$prop === $this->$prop)
                    return false;

                break;
            }
            default:
            {
                // custom validation method
                if ($this->checkMethod($this, $rule[0]) && ![ $this, $rule[0] ]($this->$prop))
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
    public function getFormFields(): string
    {
        $fields = '';

        foreach($this->attributeLabels() as $key => $value)
        {
            $fields .= "<label for=\"$key\">$value</label>\n";
            $fields .= "<input id=\"$key\" type=\"" . ($key === 'password' ? $key : 'text') . "\" placeholder=\"$key\" name=\"$this->formName[$key]\">\n";
        }

        return $fields;
    }
}
