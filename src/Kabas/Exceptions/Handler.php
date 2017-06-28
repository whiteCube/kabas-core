<?php

namespace kabas\Exceptions;

use Kabas\App;

class Handler
{
    protected $instance;
    protected $pretty;
    
    function __construct()
    {
        $this->instance = new \Whoops\Run;
        $this->pretty = new \Kabas\Exceptions\Whoops\KabasPrettyPageHandler;
        $this->instance->pushHandler($this->pretty);
    }

    public function boot()
    {
        $this->instance->register();
        $this->setErrorMode();
    }

    protected function setErrorMode()
    {
        if(!App::config()->get('app.debug')) {
            error_reporting(0);
        }
    }
}