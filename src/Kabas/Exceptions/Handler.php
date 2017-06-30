<?php

namespace kabas\Exceptions;

class Handler
{
    protected $instance;
    protected $pretty;
    
    function __construct()
    {
        $this->instance = new \Whoops\Run;
        $this->pretty = new \Kabas\Exceptions\Whoops\KabasPrettyPageHandler;
        $this->instance->pushHandler($this->pretty);
        $result = set_error_handler([$this, 'customErrorHandler'], E_ALL);
    }

    public function customErrorHandler($errno, $errstr, $errfile, $errline)
    {
        die('test');
    }

    public function boot()
    {
        $this->instance->register();
    }

}