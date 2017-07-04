<?php

namespace Kabas\Database\Json\Cache;

use Kabas\Database\ModelInterface;

class Container
{
    /**
    * All loaded ModelInterfaces
    * @var array
    */
    protected $spaces = [];

    /**
     * Checks if space exists
     * @param object $model
     * @return bool
     */
    public function has($model)
    {
        if(!($space = $this->getSpace($model))) return false;
        return count($space->getItems()) > 0;
    }
    
    /**
     * Adds or updates an empty cached item for given model
     * @param string $key
     * @param array $paths
     * @param object $model
     * @return void
     */
    public function addEmpty($key, $paths, ModelInterface $model)
    {
        $this->getOrCreateSpace($model)->make($key, $paths);
    }

    /**
     * Adds or updates multiple empty cached items for given model
     * @param array  $items
     * @param object $model
     * @return void
     */
    public function addEmpties(array $items, ModelInterface $model)
    {
        $this->getOrCreateSpace($model)->makeMultiple($items);
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
        $this->getOrCreateSpace($model)->set($key, $data, $locale);
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
        $this->getOrCreateSpace($model)->setMultiple($items, $locale);
    }

    /**
     * Returns stored item from given Space
     * @param string $key
     * @param string|Kabas\Database\ModelInterface $space
     * @param string $locale
     * @return \Kabas\Database\Json\Cache\Item|null
     */
    public function retrieve($key, $space, $locale = null)
    {
        if(!($space = $this->getSpace($space))) return;
        return $space->find($key, $locale);
    }

    /**
     * Returns all stored item from given Space
     * @param string|Kabas\Database\ModelInterface $space
     * @return array|null
     */
    public function all($space)
    {
        if(!($space = $this->getSpace($space))) return;
        return $space->getItems();
    }

    /**
     * Returns an existing or fresh Space instance
     * @param object $space
     * @return \Kabas\Database\Json\Cache\Space
     */
    public function getOrCreateSpace(ModelInterface $model)
    {
        if($existing = $this->getSpace($model->getObjectName())) {
            return $existing;
        }
        return $this->registerSpace($model);
    }

    /**
     * Returns requested existing Space instance
     * @param string|Kabas\Database\ModelInterface $space
     * @return \Kabas\Database\Json\Cache\Space|null
     */
    public function getSpace($space)
    {
        if($space instanceof ModelInterface) $space = $space->getObjectName();
        if(!isset($this->spaces[$space])) return;
        return $this->spaces[$space];
    }

    /**
     * Returns a freshly added Space instance
     * @param object $model
     * @return \Kabas\Database\Json\Cache\Space
     */
    public function registerSpace(ModelInterface $model)
    {
        return $this->spaces[$model->getObjectName()] = $this->getNewSpace($model);
    }

    /**
     * Returns an empty Space instance
     * @param object $model
     * @return \Kabas\Database\Json\Cache\Space
     */
    public function getNewSpace(ModelInterface $model)
    {
        return new Space($model->getObjectName());
    }
}