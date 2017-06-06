<?php

namespace Tests\Session;

use Kabas\Session\Handler;

class HandlerMock extends Handler
{
    static public $session = ['data' => [], 'flash' => []];

    public function start()
    {
        //  Nothing to do here since it's only a mock.
    }

    public function read()
    {
        return self::$session;
    }

    public function write(array $data, array $flash)
    {
        self::$session = ['data' => $data, 'flash' => $flash];
    }
}
