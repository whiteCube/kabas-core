<?php

namespace Kabas\Exceptions;

use \Exception;

class JsonException extends Exception
{
    public $hint = 'Please make sure the file strictly follows the JSON syntax (no extra commas allowed, double quotes, etc.)';
    public $path;

    public function __construct($path, $json, $code = 0, Exception $previous = null)
    {
        $this->path = $path;
        $message = 'Error reading JSON file "' . $path . '" ';
        parent::__construct($message, $code, $previous);
    }

}
