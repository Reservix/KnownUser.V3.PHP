<?php

namespace Reservix\Core\QueueBundle\Exceptions;

class KnownUserException extends \Exception
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }
}