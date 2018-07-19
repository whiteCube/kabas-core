<?php

namespace Kabas\Providers;

class ServiceProvider 
{
    /**
     * App Container instance
     */
    protected $app;

    /**
     * List of config files to publish
     * @var array
     */
    protected $configs;

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

    /**
     * Publish a config file
     * @param string $path The absolute path to the config file
     * @param string $name The name to save the file as
     */
    public function publishConfig($path, $name)
    {
        // TODO: set the config file to be published via a command later
        $this->configs[$name] = $path;
    }

    /**
     * Get the list of config files to publish
     * @return array
     */
    public function getConfigs()
    {
        return $this->configs;
    }

}