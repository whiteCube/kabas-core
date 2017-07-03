<?php

namespace Kabas\Exceptions;

use \Exception;

class NotFoundException extends Exception
{

    use CleansOutputBuffering;

    public $hint;
    public $path;
    
    public function __construct($identifier, $type = 'page', $code = 500, Exception $previous = null)
    {
        $this->clean();
        $this->path = $identifier;
        $this->hint = 'The specified ' . $type . ' could not be found on this server.';
        $message = ucfirst($type) . ' not found: ' . $identifier;
        parent::__construct($message, $code, $previous);
    }
}
