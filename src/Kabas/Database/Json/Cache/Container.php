<?php

namespace Kabas\Database\Json\Cache;

use Kabas\Database\ModelInterface;

class Container
{
    /**
    * All loaded models
    * @var array
    */
    protected $container = [];

    /**
    * The actual cache instance
    * @var \Kabas\Database\Json\Cache
    */
    protected static $instance;

    /**
     * Returns the cache's existing instance
     * @return \Kabas\Database\Json\Cache\Container
     */
    public static function getInstance()
    {
        if(is_null(static::$instance)) static::$instance = new static;
        return static::$instance;
    }

    /**
     * Adds or updates a cached item for given model and locale
     * @param string $key
     * @param object $data
     * @param object $model
     * @param string $locale
     * @return void
     */
    public function inject($key, $data, ModelInterface $model, $locale = null)
    {
        $this->getOrCreateNamespace($model)->set($key, $data, $locale);
    }

    /**
     * Adds or updates multiple cached items for given model and locale
     * @param array  $items
     * @param object $model
     * @param string $locale
     * @return void
     */
    public function merge(array $items, ModelInterface $model, $locale = null)
    {
        $this->getOrCreateNamespace($model)->setMultiple($items, $locale);
    }

    /**
     * Returns an existing or fresh namespace instance
     * @param object $namespace
     * @return \Kabas\Database\Json\Cache\Namespace
     */
    public function getOrCreateNamespace(ModelInterface $model)
    {
        if($existing = $this->getNamespace($model->getObjectName())) {
            return $existing;
        }
        return $this->registerNamespace($model);
    }

    /**
     * Returns requested existing namespace instance
     * @param string $name
     * @return \Kabas\Database\Json\Cache\Namespace|null
     */
    public function getNamespace(string $name)
    {
        if(!isset($this->container[$name])) return;
        return $this->container[$name];
    }

    /**
     * Returns a freshly added namespace instance
     * @param object $model
     * @return \Kabas\Database\Json\Cache\Namespace
     */
    public function registerNamespace(ModelInterface $model)
    {
        return $this->container[$model->getObjectName()] = $this->getNewNamespace($model);
    }

    /**
     * Returns an empty namespace instance
     * @param object $model
     * @return \Kabas\Database\Json\Cache\Namespace
     */
    public function getNewNamespace(ModelInterface $model)
    {
        return new Namespace($model->getObjectName(), get_class($model), true);
    }

    /**
     * Forwards static calls on the cache's instance
     * @return \Kabas\Database\Json\Cache\Container
     */
    public static function __callStatic($method, $arguments = [])
    {
        return call_user_func_array(static::getInstance(), $arguments);
    }
}