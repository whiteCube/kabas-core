<?php

namespace Kabas\Providers;

class ServiceProvider 
{
    /**
     * App Container instance
     */
    protected $app;

    /**
     * Create a Service Provider instance
     * @param Kabas\App $app
     */
    public function __construct($app) 
    {
        $this->app = $app;
    }

    /**
     * Do your magic after all Service Providers have been created.
     * @return void
     */
    public function boot() 
    {}

    /**
     * Register bindings in the container
     * @return void
     */
    public function register() 
    {}

    /**
     * Load your custom routes into the app
     * @param string $path
     * @return void
     */
    public function loadRoutesFrom($path)
    {
        // TODO: load the routes file
        require $path;
    }

    public function publishConfig($path, $name)
    {
        // TODO: set the config file to be published via a command later
    }

}