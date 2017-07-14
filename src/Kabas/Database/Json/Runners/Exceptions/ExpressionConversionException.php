<?php

namespace Kabas\Database\Json\Runners\Exceptions;

use \Exception;
use Kabas\Exceptions\CleansOutputBuffering;

class ExpressionConversionException extends Exception
{
    use CleansOutputBuffering;

    /**
     * @codeCoverageIgnore
     */
    public function __construct($expression, $format, $code = null, Exception $previous = null)
    {
        $this->clean();
        $message = 'Expression "' . $expression . '" could not be converted to ' . $format .  '.';
        parent::__construct($message, $code ?? 500, $previous);
    }

}
