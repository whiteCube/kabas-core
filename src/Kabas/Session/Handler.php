<?php

namespace Kabas\Session;

use Kabas\Exceptions\SessionCouldNotStartException;

class Handler
{
    /**
     * Namespacing for the session data
     * @var string
     */
    protected $key = 'kabas';

    /**
     * Attempts to start the session
     * @return void
     */
    public function start()
    {
        if(headers_sent()) return;
        if(!session_start()) throw new SessionCouldNotStartException();
    }

    /**
     * Reads previous data from session
     * @return array
     */ 
    public function read()
    {
        $session = isset($_SESSION[$this->key]) ? (is_array($_SESSION[$this->key]) ? $_SESSION[$this->key] : []) : [];
        return array_merge(['data' => [], 'flash' => []], $session);
    }

    /**
     * Persists the data into the session
     * @param array $data 
     * @param array $flash 
     * @return void
     */
    public function write(array $data, array $flash)
    {
        $_SESSION[$this->key] = ['data' => $data, 'flash' => $flash];
    }
}
