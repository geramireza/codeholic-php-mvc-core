<?php

namespace Codeholic\Phpmvc\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $code = 404;
    protected $message = 'Not Found Page';
}