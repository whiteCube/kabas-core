<?php

namespace Kabas\Exceptions;

use \Exception;

class ModelNotFoundException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($table, $code = 500, Exception $previous = null)
    {
        $this->clean();
        $message = "Model not found [$table]";
        parent::__construct($message, $code, $previous);
    }
}
