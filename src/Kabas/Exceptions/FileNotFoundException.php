<?php

namespace Kabas\Exceptions;

use \Exception;

class FileNotFoundException extends Exception
{
    public $hint = 'The file does not exist.';
    public $path;

    public function __construct($file, $code = 0, Exception $previous = null)
    {
        $this->path = $file;
        $message = 'File not found: ' . $file;
        $hint = $this->hint;
        parent::__construct($message, $code, $previous);
    }
}
