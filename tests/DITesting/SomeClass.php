<?php

namespace Tests\DITesting;

use Kabas\Http\Routes\Router;

class SomeClass
{
    public $instance;
    public $router;
    
    public function __construct(SomeInstance $instance, Router $router)
    {
        $this->instance = $instance;
        $this->router = $router;
    }
}