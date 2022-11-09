<?php

namespace Core\Middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();
}