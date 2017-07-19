<?php

namespace Kabas\Exceptions;

use \Exception;

class LocaleNotFoundException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;

    public function __construct($locale, $code = 500, Exception $previous = null)
    {
        $this->clean();
        $message = 'Locale "' . $locale . '" is not defined in the lang.php configuration file.';
        parent::__construct($message, $code, $previous);
    }

}
