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
     * Checks if space exists and contains items for locale
     * @param object $model
     * @param string $locale
     * @return bool
     */
    public function has($model, $locale = null)
    {
        if(!($space = $this->getSpace($model))) return false;
        if(count($space->getItemsForLocale($locale))) return true;
        return false;
    }
    
    /**
     * Adds or updates an empty cached item for given model and locale
     * @param string $key
     * @param object $path
     * @param object $model
     * @param string $locale
     * @return void
     */
    public function addEmpty($key, $path, ModelInterface $model, $locale = null)
    {
        $this->getOrCreateSpace($model)->make($key, $path, $locale);
    }

    /**
     * Adds or updates multiple empty cached items for given model and locale
     * @param array  $items
     * @param object $model
     * @param string $locale
     * @return void
     */
    public function addEmpties(array $items, ModelInterface $model, $locale = null)
    {
        $this->getOrCreateSpace($model)->makeMultiple($items, $locale);
    }

    /**
     * Loads data from cached items paths for given model and locale
     * @param object $space
     * @param string $locale
     * @return void
     */
    public function loadEmpties($space, $locale = null)
    {
        var_dump('here motherfucker');
        if(!($space = $this->getSpace($space))) return;
        $space->loadWherePaths($locale);
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
     * Returns all stored item from given Space and locale
     * @param string|Kabas\Database\ModelInterface $space
     * @param string $locale
     * @return array|null
     */
    public function all($space, $locale = null)
    {
        if(!($space = $this->getSpace($space))) return;
        return $space->getItemsForLocale($locale);
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
        return new Space($model->getObjectName(), get_class($model), true);
    }
}