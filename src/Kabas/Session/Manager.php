<?php

namespace Kabas\Session;

class Manager
{
    private $handler;

    private $data;

    private $flash;

    function __construct(Handler $handler)
    {
        $this->handler = $handler;
        $this->handler->start();
        $session = $this->handler->read();
        $this->data = new DataContainer($session['data']);
        $this->flash = new FlashContainer($session['flash']);
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->data, $method], $arguments);
    }

    public function flash($key = null, $value = null)
    {
        if(!is_null($key)) $this->flash->set($key, $value);
        return $this->flash;
    }

    public function save()
    {
        $this->handler->write($this->data->extract(), $this->flash->extract());
    }
}
