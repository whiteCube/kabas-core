<?php

namespace Kabas\Exceptions;

use \Exception;

class InvalidDriverException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($driver, $code = 0, Exception $previous = null)
    {
        $this->clean();
        $message = 'Driver "'. $driver .'"" does not exist. Available drivers: json, eloquent';
        parent::__construct($message, $code, $previous);
    }

}
