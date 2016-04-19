<?php

namespace Kabas\Exceptions;

use \Exception;
use \Kabas\App;

class TypeException extends Exception
{
      public function __construct($message, $code = 0, Exception $previous = null)
      {
            $title = 'TypeException';
            $type = $title;
            $hint = $this->showAvailableTypes();

            if(ob_get_level()) ob_clean();

            ob_start();
            include(__DIR__ . DS . 'ErrorTemplate.php');
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
            $hint = 'Available field types: <ul>';
            foreach(App::config()->fieldTypes->supportedTypes as $typeName => $type) {
                  $hint .= '<li><code>' . $typeName . '</code></li>';
            }
            $hint .= "</ul>";
            return $hint;
      }
}
