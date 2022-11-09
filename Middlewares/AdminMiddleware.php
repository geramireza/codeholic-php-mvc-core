<?php

namespace Codeholic\Phpmvc\Middlewares;

use Codeholic\Phpmvc\Application;
use Codeholic\Phpmvc\Exceptions\ForbiddenException;

class AdminMiddleware extends BaseMiddleware
{

    public function execute()
    {
        if(!Application::isAdmin()){
            throw new ForbiddenException();
        }
    }
}