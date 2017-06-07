<?php

namespace Kabas\Exceptions;

use \Exception;

class FileNotFoundException extends Exception
{
    public function __construct($file, $code = 0, Exception $previous = null)
    {
        $message = 'File not found: ' . $file;
        parent::__construct($message, $code, $previous);
    }
}
