<?php

namespace Kabas\Exceptions;

use \Exception;
use \Kabas\App;
use Seld\JsonLint\JsonParser;

class JsonException extends Exception
{
      public function __construct($path, $json, $code = 0, Exception $previous = null)
      {
            $parser = new JsonParser();
            $lint = $parser->lint($json)->getMessage();
            $title = 'JsonException ('. json_last_error_msg() .')';
            $type = $title;
            $message = 'Error reading JSON file <code>"' . $path . '"</code>. ';
            $hint = 'Please make sure the file strictly follows the JSON syntax (no extra commas allowed, double quotes, etc.)';

            if(ob_get_level()) ob_clean();

            ob_start();
            include(__DIR__ . DS . 'ErrorTemplate.php');
            $template = ob_get_clean();

            parent::__construct($template, $code, $previous);
      }
}
