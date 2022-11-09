<?php

namespace Codeholic\Phpmvc;

abstract class Model extends Eloquent
{
    public const RULE_REQUIRE = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    private array $errors = [];
    public function load($data):void
    {
        foreach ($data as $key => $value){
            if(property_exists($this,$key)){
                $this->{$key} = $value;
            }
        }
    }
    abstract public function rules();
    abstract public function labels();
    public function validate($rules,$data):bool
    {
        foreach ($rules as $attribute => $ruleset){
            $value = $data[$attribute];
            foreach ($ruleset as $rule){
                $ruleName = $rule;
                if(is_array($rule)){
                    $ruleName = $rule[0];
                }

                if($ruleName === self::RULE_REQUIRE && $value === ''){
                    $this->addErrorForRule($attribute,self::RULE_REQUIRE);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attribute,self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){
                    $this->addErrorForRule($attribute,self::RULE_MIN,$rule);
                }
                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']){
                    $this->addErrorForRule($attribute,self::RULE_MAX,$rule);
                }
                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}){
                    $this->addErrorForRule($attribute,self::RULE_MATCH,$rule);
                }
                if($ruleName === self::RULE_UNIQUE ){
                    $result = $this->first($attribute,$value,$rule['unique']);
                    if($result){
                        $this->addErrorForRule($attribute,self::RULE_UNIQUE,$rule);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function hasError(string $attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function firstError(string $attribute)
    {
        return $this->errors[$attribute][0] ?? null;
    }

    private function addErrorForRule(string $attribute, string $ruleName,$params = [])
    {
        $message = $this->errorMessages()[$ruleName];
        foreach ($params as $key => $value){
            $message = str_replace("{{$key}}", $this->labels()[$value] ?? $value ,$message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function addError(string $attribute,string $message)
    {
        $this->errors[$attribute][] = $message;
    }
    private function errorMessages():array
    {
        return [
          self::RULE_REQUIRE => 'This field is required',
          self::RULE_EMAIL => 'This field must be a valid email address',
          self::RULE_MIN => 'This field must be at least {min} characters',
          self::RULE_MAX => 'This field must be less than {max} characters',
          self::RULE_MATCH => 'This field must be match with {match} field',
          self::RULE_UNIQUE => 'This field already exist',
        ];
    }

}