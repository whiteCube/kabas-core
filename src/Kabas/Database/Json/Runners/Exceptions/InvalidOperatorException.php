<?php

namespace Kabas\Database\Json\Runners\Exceptions;

use \Exception;
use Kabas\Exceptions\CleansOutputBuffering;

class InvalidOperatorException extends Exception
{
    use CleansOutputBuffering;

    public function __construct($operator, $code = 500, Exception $previous = null)
    {
        $this->clean();
        $message = 'Operator "'. $operator .'"" does not exist or is not available when using JSON models';
        parent::__construct($message, $code, $previous);
    }

}
