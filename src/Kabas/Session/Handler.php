<?php

namespace Kabas\Session;

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
        if(!session_start()) throw new \Exception('Unable to start sessions');
    }

    /**
     * Reads previous data from session
     * @return array
     */ 
    public function read()
    {
        return array_merge(['data' => [], 'flash' => []], $_SESSION[$this->key] ?? []);
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
