<?php

namespace Kabas\Exceptions;

use \Exception;

class FileNotFoundException extends Exception
{

    use CleansOutputBuffering;

    public $hint = 'The file does not exist.';
    public $path;

    public function __construct($file, $code = 500, Exception $previous = null)
    {
        $this->clean();
        $this->path = $file;
        $message = 'File not found: ' . $file;
        $hint = $this->hint;
        parent::__construct($message, $code, $previous);
    }
}
