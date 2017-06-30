<?php

namespace Kabas\Exceptions;

use \Exception;

class NoResponseException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($code = 0, Exception $previous = null)
    {
        $this->clean();
        $message = 'No response was sent to the current request.';
        parent::__construct($message, $code, $previous);
    }

}
