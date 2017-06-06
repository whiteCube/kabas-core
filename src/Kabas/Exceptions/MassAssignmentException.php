<?php

namespace Kabas\Exceptions;

use \Exception;

class MassAssignmentException extends Exception
{
    public function __construct($key, $code = 0, Exception $previous = null)
    {
        $message = "Mass assignment exception [$key]";
        parent::__construct($message, $code, $previous);
    }
}
