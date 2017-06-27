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
     * @param bool $withData
     * @return this
     */
    protected function loadModelCache($locale, $withData = false)
    {
        $model = $this->query->getModel();
        if(!Cache::has($model)) {
            Cache::addEmpties($this->getModelScan($model, $locale), $model);
        }
        if($withData) {
            Cache::loadEmpties($model);
        }
        $this->cached = Cache::all($model);
        $this->stack = $this->cached;
        return $this;
    }

    /**
     * Returns all keys with paths from the model's repository
     * @param Kabas\Database\Json\Model $model
     * @param string $locale
     * @return array
     */
    protected function getModelScan($model, $locale)
    {
        return File::scanJsonFromDir($model->getRepositoryPath($locale), true);
    }
}