<?php

namespace Kabas\Exceptions;

use \Exception;

class NotFoundException extends Exception
{
    public function __construct($identifier, $type = 'page', $code = 0, Exception $previous = null)
    {
        $message = ucfirst($type) . ' not found: ' . $identifier;
        parent::__construct($message, $code, $previous);
    }
}
