<?php

namespace Kabas\Database\Json\Runners\Exceptions;

use \Exception;
use Kabas\Exceptions\CleansOutputBuffering;
use Kabas\Database\Json\Runners\Operators\OperatorInterface;

class InvalidExpressionException extends Exception
{
    use CleansOutputBuffering;

    public function __construct(OperatorInterface $operator, $code = 500, Exception $previous = null)
    {
        $this->clean();
        $message = 'Operator "'. $operator->getName() .'" has an invalid expression "' . $operator->getExpressionString() . '"';
        parent::__construct($message, $code, $previous);
    }

}
