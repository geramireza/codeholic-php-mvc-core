<?php

namespace Core;

abstract class Eloquent
{
    public Database $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    abstract public static function tableName();
    abstract public function attributes():array;
    public function save():bool
    {
        $tableName = static::tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attribute) => ":$attribute",$attributes);
        $sql = "INSERT INTO $tableName (".implode(',',$attributes).") VALUES (".implode(',',$params).")";
        $stmt = self::prepare($sql);
        foreach ($attributes as $attribute){
            $property = $this->getProperty($attribute);
            $stmt->bindValue(":$attribute",$this->{$property});
        }
        return $stmt->execute();
    }

    public static function prepare(string $sql)
    {
       return Application::$app->database->pdo->prepare($sql);
    }

    private function getProperty(mixed $attribute)
    {
        $property = lcfirst(str_replace('_','',ucwords($attribute,'_')));
        return property_exists($this,$property) ? $property : $attribute;
    }

    public static function first($attribute,$value,$table = null)
    {
        $table  = $table ? $table : static::tableName();

        $stmt = self::prepare("SELECT * FROM $table WHERE $attribute = :$attribute");
        $stmt->bindValue(":$attribute",$value);
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }
}