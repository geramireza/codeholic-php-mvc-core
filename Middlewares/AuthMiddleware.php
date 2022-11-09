<?php

namespace Codeholic\Phpmvc\Middlewares;

use Codeholic\Phpmvc\Application;
use Codeholic\Phpmvc\Exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions;
    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if(Application::isGuest()){
            if(empty($this->actions) || in_array(Application::$app->controller->getAction(),$this->actions)){
                throw new ForbiddenException();
            }
        }
    }
}