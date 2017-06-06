<?php

namespace Kabas\Session;

class Handler
{
    protected $key = 'kabas';

    public function start()
    {
        if(headers_sent()) return;
        if(!session_start()) throw new \Exception('Unable to start sessions');
    }

    public function read()
    {
        return array_merge(['data' => [], 'flash' => []], $_SESSION[$this->key] ?? []);
    }

    public function write(array $data, array $flash)
    {
        $_SESSION[$this->key] = ['data' => $data, 'flash' => $flash];
    }
}
