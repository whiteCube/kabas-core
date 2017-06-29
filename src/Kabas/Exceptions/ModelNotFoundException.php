<?php

namespace Kabas\Exceptions;

use \Exception;

class ModelNotFoundException extends Exception
{
    public $hint;
    public $path;

    public function __construct($table, $code = 0, Exception $previous = null)
    {
        $message = "Model not found [$table]";
        parent::__construct($message, $code, $previous);
    }
}
