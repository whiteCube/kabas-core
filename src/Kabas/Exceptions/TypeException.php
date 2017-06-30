<?php

namespace Kabas\Exceptions;

use \Exception;
use \Kabas\App;

class TypeException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        $this->clean();
        $this->hint = $message;
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

}
