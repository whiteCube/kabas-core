<?php

namespace Kabas\Database\Json\Cache;

class Facade
{
    /**
    * The actual cache instance
    * @var \Kabas\Database\Json\Cache\Container
    */
    protected static $instance;

    /**
     * Returns the cache's existing instance
     * @return \Kabas\Database\Json\Cache\Container
     */
    public static function getInstance()
    {
        if(is_null(static::$instance)) static::$instance = new Container();
        return static::$instance;
    }

    /**
     * Forwards static calls on the cache's instance
     * @return \Kabas\Database\Json\Cache\Container
     */
    public static function __callStatic($method, $arguments = [])
    {
        return call_user_func_array([static::getInstance(), $method], $arguments);
    }
}