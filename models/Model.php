<?php

namespace models;

class Model
{
    private Model $model;

    /**
     * Model constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getForm(string $url): string
    {
        $form = '<form id="' . $this->formName() . '" action="' . $url . '" method="POST">' . "\n";

        $form .= $this->model->getFormFields();

        $form .= "    </form>\n";

        return $form;
    }

    public function load(array $data): bool
    {
        $form_name = $this->formName();

        if (isset($data[$form_name]))
            return $this->model->populate($data[$form_name]);

        return false;
    }

    private function formName(): string
    {
        return explode('\\', get_class($this->model))[1];
    }
}
