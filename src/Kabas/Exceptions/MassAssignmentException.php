<?php

namespace Kabas\Exceptions;

use \Exception;

class MassAssignmentException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($key, $code = 500, Exception $previous = null)
    {
        $this->clean();
        $message = "Mass assignment exception [$key]";
        parent::__construct($message, $code, $previous);
    }
}
