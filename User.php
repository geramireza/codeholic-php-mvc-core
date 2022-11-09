<?php

namespace Core;

abstract class User extends Model
{
    abstract public static function PrimaryKey();
    abstract public static function getFullName();
}