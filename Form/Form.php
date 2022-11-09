<?php

namespace Core\Form;

use Core\Model;

class Form
{
    public static function begin(string $action = '', string $method = 'post')
    {
        echo "<form action='$action' method='$method'>";
    }

    public static function end()
    {
        echo '</form>';
    }

    public static function input(Model $model,string $attribute):string
    {
        return  Input::make($model,$attribute);
    }

    public static function textarea(Model $model,string $attribute):string
    {
        return new TextArea($model,$attribute);
    }
}