<?php

namespace Theme\TheCapricorn\Providers\Package;

use Kabas\Http\Routes\Router;

class SomeService
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function work()
    {
        var_dump($this->router);
        return 'working';
    }
}