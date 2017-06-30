<?php

namespace Kabas\Exceptions;

use \Exception;

class JsonException extends Exception
{

    use CleansOutputBuffering;

    public $hint = 'Please make sure the file strictly follows the JSON syntax (no extra commas allowed, double quotes, etc.)';
    public $path;

    public function __construct($path, $json, $code = 0, Exception $previous = null)
    {
        $this->clean();
        $this->path = $path;
        $message = 'Error reading JSON file "' . $path . '" ';
        parent::__construct($message, $code, $previous);
    }

}
