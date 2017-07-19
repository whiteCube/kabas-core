<?php

namespace Kabas\Database\Json\Runners\Exceptions;

use \Exception;
use Kabas\Exceptions\CleansOutputBuffering;
use Kabas\Database\Json\Runners\Operators\OperatorInterface;

class ExpressionTypeException extends Exception
{
    use CleansOutputBuffering;

    public function __construct($value, $code = null, Exception $previous = null)
    {
        $this->clean();
        $message = 'Value of type ' . gettype($value) . ' cannot be used as an expression in its operator context.';
        parent::__construct($message, $code ?? 500, $previous);
    }

}
