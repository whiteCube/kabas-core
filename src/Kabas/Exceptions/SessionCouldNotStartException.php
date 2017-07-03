<?php

namespace Kabas\Exceptions;

use \Exception;

class SessionCouldNotStartException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($code = 500, Exception $previous = null)
    {
        $this->clean();
        $message = 'Session could not be started. Please check your server configuration.';
        parent::__construct($message, $code, $previous);
    }

}
