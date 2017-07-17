<?php

namespace Kabas\Exceptions;

use \Exception;

class CommandNotFoundException extends Exception
{

    public $hint;
    public $path;

    public function __construct($code = 500, Exception $previous = null)
    {
        $message = "\n\033[31mKabas: Command not found!\nUse \"php kabas help\" to view available commands.\n";
        parent::__construct($message, $code, $previous);
    }

}
