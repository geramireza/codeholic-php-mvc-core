<?php

namespace Codeholic\Phpmvc\Middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}