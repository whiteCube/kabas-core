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
            $type = 'JsonException ('. json_last_error_msg() .')';
            $message = 'Error reading JSON file <code>"' . $path . '"</code>. ';
            $hint = 'Please make sure the file strictly follows the JSON syntax (no extra commas allowed, double quotes, etc.)';

            ob_start();
            include(__DIR__ . DS . 'Error.php');
            $template = ob_get_clean();

            parent::__construct($template, $code, $previous);
      }

      /**
       * Show the concerned field and its template in the error message.
       * @param string $fieldName
       * @param string $viewID
       */
      public function setFieldName($fieldName, $viewID)
      {
            $this->message = $this->message . '<pre><strong>field "' . $fieldName . '" in template "' . $viewID . '"</strong></pre>';
      }

      /**
       * Show the currently supported field types.
       * @return void
       */
      public function showAvailableTypes()
      {
            $this->message = $this->message . '<pre>Available field types: ';
            foreach(App::config()->fieldTypes->supportedTypes as $typeName => $type) {
                  $this->message = $this->message . '<br>' . $typeName;
            }
            $this->message .= "</pre>";
      }
}
