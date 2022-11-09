<?php

namespace Core\Middlewares;

use Core\Application;
use Core\Exceptions\ForbiddenException;

class AdminMiddleware extends BaseMiddleware
{

    public function execute()
    {
        if(!Application::isAdmin()){
            throw new ForbiddenException();
        }
    }
}