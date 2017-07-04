<?php

namespace Kabas\Database\Json\Cache;

class Space
{
    /**
    * Space identifier
    * @var string
    */
    protected $name;

    /**
    * Cached items
    * @var array
    */
    protected $container = [];

    /**
     * Create a new Space instance
     * @param string $name
     * @return void
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * Initializes empty stored item for all its locales
     * @param string $key
     * @param mixed  $paths
     * @return \Kabas\Database\Json\Cache\Item
     */
    public function make($key, $paths) {
        return $this->findOrCreate($key)->setPaths($paths);
    }

    /**
     * Initializes emultiple mpty stored items
     * @param array  $items
     * @return void
     */
    public function makeMultiple($items) {
        foreach ($items as $key => $paths) {
            $this->make($key, $paths);
        }
    }

    /**
     * Adds or updates stored item for given locale
     * @param string $key
     * @param mixed  $data
     * @param string $locale
     * @return \Kabas\Database\Json\Cache\Item
     */
    public function set($key, $data, $locale = null) {
        return $this->findOrCreate($key)->set($data, $locale);
    }

    /**
     * Adds or updates multiple stored items for given locale
     * @param array  $items
     * @param string $locale
     * @return void
     */
    public function setMultiple(array $items, $locale = null) {
        foreach ($items as $key => $data) {
            $this->set($key, $data, $locale);
        }
    }

    /**
     * Retrieves stored item for given locale
     * @param string $key
     * @return \Kabas\Database\Json\Cache\Item
     */
    public function find($key) {
        if(!isset($this->container[$key])) return;
        return $this->container[$key];
    }

    /**
     * Retrieves or creates new stored item
     * @param string $key
     * @return \Kabas\Database\Json\Cache\Item
     */
    public function findOrCreate($key) {
        if($existing = $this->find($key)) return $existing;
        return $this->registerNewEmptyItem($key);
    }

    /**
     * Adds and returns a new stored item
     * @param string $key
     * @return \Kabas\Database\Json\Cache\Item
     */
    protected function registerNewEmptyItem($key) {
        $item = $this->getNewEmptyItem()->setKey($key);
        $this->container[$key] = $item;
        return $item;
    }

    /**
     * Retrieves locale repository
     * @return array
     */
    public function getItems() {
        return $this->container;
    }

    /**
     * Creates a new and empty stored item instance
     * @return \Kabas\Database\Json\Cache\Item
     */
    protected function getNewEmptyItem() {
        return new Item($this);
    }
}