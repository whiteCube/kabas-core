<?php

namespace Kabas\Database\Json\Cache;

use Kabas\Utils\Lang;

class Namespace
{
    /**
    * Namespace identifier
    * @var string
    */
    protected $name;

    /**
    * Underlying object classname
    * @var string
    */
    protected $class;

    /**
    * Indicates if the items should be
    * stored under different locale identifiers
    * @var boolean
    */
    protected $translatable;

    /**
    * Cached items
    * @var array
    */
    protected $container = [];

    /**
     * Create a new namespace instance
     * @param string $name
     * @param string $classname
     * @param bool   $translatable
     * @return void
     */
    public function __construct($name, $classname, $translatable = true) {
        $this->name = $name;
        $this->classname = $classname;
        $this->translatable = $translatable;
    }

    /**
     * Adds or updates stored item for given locale
     * @param string $key
     * @param mixed  $data
     * @param string $locale
     * @return \Kabas\Database\Json\Cache\Item
     */
    public function set($key, $data, $locale = null) {
        return $this->findOrCreate($key, $locale)->set($data);
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
     * @param string $locale
     * @return \Kabas\Database\Json\Cache\Item
     */
    public function find($key, $locale = null) {
        $repository = $this->getItemsForLocale($locale) ?? [];
        if(!isset($repository[$key])) return;
        return $repository[$key];
    }

    /**
     * Retrieves or creates new stored item for given locale
     * @param string $key
     * @param string $locale
     * @return \Kabas\Database\Json\Cache\Item
     */
    public function findOrCreate($key, $locale = null) {
        if($existing = $this->find($key, $locale)) return $existing;
        return $this->registerNewEmptyItem($key, $locale);
    }

    /**
     * Adds and returns a new stored item for given locale
     * @param string $key
     * @param string $locale
     * @return \Kabas\Database\Json\Cache\Item
     */
    protected function registerNewEmptyItem($key, $locale = null) {
        $repository = $this->getOrCreateLocaleRepository($locale);
        return $repository[$key] = $this->getNewEmptyItem()->setKey($key);
    }

    /**
     * Retrieves or adds locale repository
     * @param string $locale
     * @return array
     */
    protected function getOrCreateLocaleRepository($locale = null) {
        if(is_array($existing = $this->getItemsForLocale($locale))) {
            return $existing;
        }
        return $this->container[$this->getLocaleIdentifier($locale)] = [];
    }

    /**
     * Retrieves locale repository
     * @param string $locale
     * @return array|null
     */
    protected function getItemsForLocale($locale = null) {
        if(!$this->translatable) return $this->container;
        $locale = $this->getLocaleIdentifier($locale);
        if(!isset($this->container[$locale])) return;
        return $this->container[$locale];
    }

    /**
     * Transforms given locale to common locale syntax
     * @param string $locale
     * @return string
     */
    protected function getLocaleIdentifier($locale = null) {
        return Lang::getOrDefault($locale)->original;
    }

    /**
     * Creates a new and empty stored item instance
     * @return \Kabas\Database\Json\Cache\Item
     */
    protected function getNewEmptyItem() {
        return new Item($this);
    }
}