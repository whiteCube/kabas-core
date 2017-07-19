<?php

namespace Kabas\Database\Json\Runners\Exceptions;

use \Exception;
use Kabas\Exceptions\CleansOutputBuffering;

class InvalidOperatorException extends Exception
{
    use CleansOutputBuffering;

    /**
     * @codeCoverageIgnore
     */
    public function __construct($operator, $code = null, Exception $previous = null)
    {
        $this->clean();
        $message = 'Operator "' . $operator . '" does not exist or is not available when using JSON models.';
        parent::__construct($message, $code ?? 500, $previous);
    }

}
