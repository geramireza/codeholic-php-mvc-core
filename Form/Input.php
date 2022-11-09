<?php

namespace Codeholic\Phpmvc\Form;

use Codeholic\Phpmvc\Model;

class Input extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_EMAIL = 'email';

    public string $type = self::TYPE_TEXT;

    public function password()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function email()
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    public static function make(Model $model,string $attribute)
    {
        return new self($model,$attribute);
    }
    public function renderInput():string
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control %s" >',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
        );
    }
}