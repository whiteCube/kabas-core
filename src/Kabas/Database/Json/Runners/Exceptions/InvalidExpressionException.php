<?php

namespace Kabas\Database\Json\Runners\Exceptions;

use \Exception;
use Kabas\Exceptions\CleansOutputBuffering;
use Kabas\Database\Json\Runners\Operators\OperatorInterface;

class InvalidExpressionException extends Exception
{
    use CleansOutputBuffering;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(OperatorInterface $operator, $code = null, Exception $previous = null)
    {
        $this->clean();
        $message = 'Operator "' . $operator->getName() . '" for key type "' . $operator->getType() . '" has an invalid expression "' . $operator->getExpressionString() . '"';
        parent::__construct($message, $code ?? 500, $previous);
    }

}
