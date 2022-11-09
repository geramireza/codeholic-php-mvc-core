<?php

namespace Codeholic\Phpmvc\Form;

use Codeholic\Phpmvc\Model;

class TextArea extends BaseField
{
    public function renderInput(): string
    {
        return sprintf(
            '<textarea name="%s" rows="10" class="form-control %s">%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->model->{$this->attribute},
        );
    }

    public static function make(Model $model, string $attribute): string
    {
        return new self($model,$attribute);
    }
}