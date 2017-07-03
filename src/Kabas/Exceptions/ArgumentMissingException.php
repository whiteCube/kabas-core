<?php

namespace Kabas\Exceptions;

use \Exception;

class ArgumentMissingException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($context, $message, $code = 500, Exception $previous = null)
    {
        $this->clean();
        $message = 'Argument missing for ' . $context . ': ' . $message;
        parent::__construct($message, $code, $previous);
    }

}
