<?php

namespace Kabas\Exceptions;

use \Exception;
use \Kabas\App;
use Seld\JsonLint\JsonParser;

class JsonException extends Exception
{
    public $hint = 'Please make sure the file strictly follows the JSON syntax (no extra commas allowed, double quotes, etc.)';
    public $path;

    public function __construct($path, $json, $code = 0, Exception $previous = null)
    {
        $this->path = $path;
        $parser = new JsonParser();
        $lint = $parser->lint($json)->getMessage();
        $title = 'JsonException ('. json_last_error_msg() .')';
        $type = $title;
        $message = 'Error reading JSON file "' . $path . '" ';
        $hint = $this->hint;

        if(ob_get_level()) ob_clean();

        ob_start();
        include(__DIR__ . DS . 'ErrorTemplate.php');
        $template = ob_get_clean();

        parent::__construct($message, $code, $previous);
    }

}
