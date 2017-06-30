<?php

namespace Kabas\Exceptions;

use \Exception;

class TypeException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        $this->clean();
        $this->hint = $message;
        parent::__construct($message, $code, $previous);
    }

}
