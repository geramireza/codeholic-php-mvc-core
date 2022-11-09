<?php

namespace Codeholic\Phpmvc\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    protected $code = 403;
    protected $message = 'You cannot access to this page. you don\'t have permission';
}