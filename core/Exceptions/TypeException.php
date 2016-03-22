<?php

namespace Kabas\Exceptions;

use \Exception;

class TypeException extends Exception
{
      public function __construct($message, $code = 0, Exception $previous = null)
      {
            $message = '<pre>🤔 <span style="color: red;">Kabas TypeException:</span> ' . $message . '</pre>';
            parent::__construct($message, $code, $previous);
      }
}
