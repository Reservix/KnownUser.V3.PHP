<?php

namespace Reservix\Core\QueueBundle\Utils;

class Utils
{
    public static function isNullOrEmptyString($value)
    {
        return (!isset($value) || trim($value) === '');
    }
}
