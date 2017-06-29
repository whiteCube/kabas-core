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
    }
    
}