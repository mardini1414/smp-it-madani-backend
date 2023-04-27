<?php

namespace App\Exceptions;

class UnauthorizedException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}