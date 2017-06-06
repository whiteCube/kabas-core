<?php

namespace Kabas\Session;

class Manager
{
    /**
     * The session handler
     * @var Handler
     */
    private $handler;

    /**
     * A wrapper for the session data currently in memory
     * @var DataContainer
     */
    private $data;

    /**
     * A wrapper for the flash data currently in memory
     * @var FlashContainer
     */
    private $flash;

    /**
     * Constructs a Session Manager instance
     * @param Handler $handler 
     * @return void
     */
    function __construct(Handler $handler)
    {
        $this->handler = $handler;
        $this->handler->start();
        $session = $this->handler->read();
        $this->data = new DataContainer($session['data']);
        $this->flash = new FlashContainer($session['flash']);
    }

    /**
     * Forwards method calls to DataContainer
     * @param string $method 
     * @param mixed $arguments 
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->data, $method], $arguments);
    }

    /**
     * Stores a key/value pairing in flash session
     * or returns FlashContainer instance
     * @param mixed|null $key 
     * @param mixed|null $value 
     * @return mixed
     */
    public function flash($key = null, $value = null)
    {
        if(!is_null($key)) $this->flash->set($key, $value);
        return $this->flash;
    }

    /**
     * Uses the session handler to persist the session and flash data
     * @return void
     */
    public function save()
    {
        $this->handler->write($this->data->extract(), $this->flash->extract());
    }
}
