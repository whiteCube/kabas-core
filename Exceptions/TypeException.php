<?php

namespace Kabas\Exceptions;

use \Exception;
use \Kabas\App;

class TypeException extends Exception
{
      public function __construct($message, $code = 0, Exception $previous = null)
      {
            $message = '<pre>🤔 <span style="color: red;">Kabas TypeException: </span> ' . $message . '</pre>';
            parent::__construct($message, $code, $previous);
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

            foreach(App::config()->fieldTypes->types as $typeName => $type) {
                  $this->message = $this->message . '<br>' . $typeName;
            }
            $this->message .= "</pre>";
      }
}
