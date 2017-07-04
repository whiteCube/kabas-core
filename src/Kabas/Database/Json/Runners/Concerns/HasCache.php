<?php

namespace Kabas\Database\Json\Runners\Concerns;

use Kabas\Utils\File;
use Kabas\Database\Json\Cache\Facade as Cache;

trait HasCache
{
    /**
    * The full items cache
    * @var array
    */
    protected $cached = [];

    /**
     * Puts all items for model in global cache
     * @return this
     */
    protected function loadModelCache()
    {
        $model = $this->query->getModel();
        if(!Cache::has($model)) {
            Cache::addEmpties($this->getModelScan($model), $model);
        }
        $this->cached = Cache::all($model);
        $this->stack = $this->cached;
        return $this;
    }

    /**
     * Returns all keys with paths from the model's repository
     * @param Kabas\Database\Json\Model $model
     * @return array
     */
    protected function getModelScan($model)
    {
        $items = [];
        foreach ($model->getRepositories() as $locale => $path) {
            $items = $this->mergeInRepository($path, $locale, $items);
        }
        return $items;
    }

    /**
     * Adds locale files from given repository to the model's scan array
     * @param string $path
     * @param string $locale
     * @param array $items
     * @return array
     */
    protected function mergeInRepository($path, $locale, $items) {
        foreach (File::scanJsonFromDir($path, true) as $key => $file) {
            if(!isset($items[$key])) $items[$key] = [];
            $items[$key][$locale] = $file;
        }
        return $items;
    }
}