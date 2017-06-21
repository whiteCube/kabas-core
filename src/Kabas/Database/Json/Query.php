<?php

namespace Kabas\Database\Json;

use Kabas\Utils\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Processors\Processor;

class Query extends Builder
{
    /**
    * The current model to query on
    * @var object
    */
    protected $model;

    /**
    * All open models'cache
    * @var array
    */
    protected static $cache = [];

    /**
     * Create a new query builder instance.
     * @return void     * @param  \Illuminate\Database\Query\Processors\Processor  $processor
     */
    public function __construct(Model $model, Processor $processor = null)
    {
        $this->model = $model;
        $this->processor = $processor;
    }

    /**
     * Run the query as a "select" statement
     * @return array
     */
    protected function runSelect()
    {
        $items = $this->getCached($this->hasOtherWheresThanKey());
        return $this->applyLimit($items);
    }

    /**
     * Checks if the query should perform any
     * other where statement than on the primary key
     * @return boolean
     */
    protected function hasOtherWheresThanKey()
    {
        if(!$this->wheres) return false;
        // TODO : check where statements
        return true;
    }

    /**
     * Returns all objects for this model
     * @param boolean $withFilesContent
     * @return boolean
     */
    protected function getCached($withFilesContent = false)
    {
        if(!isset(static::$cache[$this->from])) {
            static::$cache[$this->from] = $this->loadEmptyCache();
        }
        if($withFilesContent) {
            $this->fillCache(static::$cache[$this->from]);
        } 
        return static::$cache[$this->from];
    }

    /**
     * Returns all keys with no associated data from repository
     * @return array
     */
    protected function loadEmptyCache()
    {
        return File::scanJsonFromDir($this->model->getRepositoryPath(), true);
    }

    /**
     * Adds data to every key of the cache
     * @param array $cache
     * @return void
     */
    protected function fillCache(&$cache)
    {
        foreach ($cache as $path => $key) {
            if(is_array($key)) continue;
            if(is_null($content = File::loadJsonIfValid($path))) continue;
            $cache[$path] = ['key' => $key, 'data' => $content];
        }
    }

    /**
     * Returns the first amount of items from given stack
     * according to the current limit and offset.
     * @param array $stack
     * @return array
     */
    protected function applyLimit(array $stack)
    {
        if(!$this->limit) return $stack;
        return array_slice($stack, $this->offset ?? 0, $this->limit, true);
    }
}