<?php

namespace Codeholic\Phpmvc\Form;

use Codeholic\Phpmvc\Model;

abstract class BaseField
{
    public Model $model;
    public string $attribute;
    abstract public function renderInput():string;
    abstract public static function make(Model $model, string $attribute);
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString(): string
    {
        return sprintf('<div class="mb-3">
                <label class="form-label">%s:</label>
                    %s
                <div class="invalid-feedback">
                    <div>
                        %s
                    </div>
                </div>
            </div>
      ',
            $this->model->labels()[$this->attribute],
            $this->renderInput(),
            $this->model->firstError($this->attribute)
        );
    }


}