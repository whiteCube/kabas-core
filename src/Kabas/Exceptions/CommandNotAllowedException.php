<?php

namespace Kabas\Exceptions;

use \Exception;

class CommandNotAllowedException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        $this->clean();
        $message = 'Command not allowed: ' . $message;
        parent::__construct($message, $code, $previous);
    }

}
