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
     * List of views to publish
     * @var array
     */
    protected $views;

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
        require $path;
    }

    /**
     * Set a config to be published
     * @param string $path The absolute path to the config file
     * @param string $name The name to save the file as
     */
    public function publishConfig($path, $name)
    {
        $this->configs[$name] = $path;
    }

    /**
     * Set a view or view folder to be published
     * @param $path The absolute path to the config file or folder
     * @param $dest the folder name to save the files to
     */
    public function publishViews($path, $dest)
    {
        if(!isset($this->views[$dest])) $this->views[$dest] = [];
        $this->views[$dest][] = $path;
    }

    /**
     * Get the list of config files to publish
     * @return array
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * Get the list of views to publish
     * @return array
     */
    public function getViews()
    {
        return $this->views;
    }

}