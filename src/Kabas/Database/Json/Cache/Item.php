<?php

namespace Kabas\Database\Json\Cache;

use Kabas\Utils\File;
use Kabas\Utils\Lang;

class Item
{
    /**
    * Identifier
    * @var string
    */
    public $key;

    /**
    * contained data
    * @var array
    */
    public $data = [];

    /**
    * paths to item's files
    * @var array
    */
    public $paths = [];

    /**
    * Item's cache Space
    * @var \Kabas\Database\Json\Cache\Space
    */
    protected $space;

    /**
     * Create a new cache item instance
     * @param string $name
     * @param string $classname
     * @param bool   $translatable
     * @return void
     */
    public function __construct(Space $space) {
        $this->space = $space;
    }

    /**
     * Updates data for given locale
     * @param mixed  $data
     * @param string $locale
     * @return this
     */
    public function set($data, $locale = null) {
        $this->data[$this->getSharedOrLocale($locale)] = $data;
        return $this;
    }

    /**
     * Returns a stdClass representation of the currently 
     * stored data for given locale
     * @param string $locale
     * @return \stdClass
     */
    public function get($locale = null) {
        return $this->mergeDataObjects(
            $this->getDataAsObject(SHARED_DIR),
            $this->getDataAsObject($this->getLocaleIdentifier($locale))
        );
    }

    /**
     * Only returns data for given locale or shared if no locale given
     * @param string $locale
     * @return mixed
     */
    public function getData($locale = null) {
        if(is_null($locale)) $locale = SHARED_DIR;
        if(!isset($this->data[$locale]) && !isset($this->paths[$locale])) return;
        if(is_null($this->data[$locale])) $this->load($locale);
        return $this->data[$locale];
    }

    /**
     * Updates file paths for cached item
     * @param array $path
     * @return this
     */
    public function setPaths($paths) {
        foreach ($paths as $locale => $path) {
            $this->paths[$locale] = realpath($path);
            $this->data[$locale] = null;
        }
        return $this;
    }

    /**
     * Updates identifier key for cached item
     * @param string $key
     * @return this
     */
    public function setKey($key) {
        $this->key = $key;
        return $this;
    }

    /**
     * Sets data from item's file content
     * @param string $key
     * @return this
     */
    public function load($key) {
        if(is_null($content = File::loadJsonIfValid($this->paths[$key], false))) return $this->set(false, $key);
        return $this->set($content, $key);
    }

    /**
     * Transforms this item to stdClass
     * @param string $key
     * @param string $locale
     * @return \stdClass
     */
    public function toDataObject($key, $locale = null) {
        $item = $this->get($locale);
        $item->{$key} = $this->key;
        return $item;
    }

    /**
     * Returns a stdClass representation of the currently stored data
     * @param string $locale
     * @return \stdClass
     */
    protected function getDataAsObject($locale) {
        $value = $this->getData($locale);
        if(is_null($value) || $value === false) return new \stdClass();
        if(is_object($value)) return $value;
        if(is_array($value)) return (object) $value;
        $data = new \stdClass;
        $data->value = $value;
        return $data;
    }

    /**
     * Returns a new object containing merged values from two original objects
     * @param object $first
     * @param object $second
     * @return \stdClass
     */
    protected function mergeDataObjects($first, $second) {
        // First we'll merge the "data" attribute from the first given object
        // into the second. This way, developpers can have untranslatable
        // fields nested under the shared object and add them to the 
        // translated fields from the locale object.
        if(is_object($first->data ?? null) && is_object($second->data ?? null)) {
            $second->data = (object) array_merge((array) $first->data, (array) $second->data);
        }
        // Now let's merge all first-level items from the second object
        // into the first, which will give priority to values from the
        // second object when conflicts are encountered.
        return (object) array_merge((array) $first, (array) $second);
    }

    /**
     * Transforms given locale to common locale syntax or "shared" if null
     * @param string $locale
     * @return string
     */
    protected function getSharedOrLocale($locale = null) {
        if(is_null($locale) || $locale == SHARED_DIR) return SHARED_DIR;
        return $this->getLocaleIdentifier($locale);
    }

    /**
     * Transforms given locale to common locale syntax
     * @param string $locale
     * @return string
     */
    protected function getLocaleIdentifier($locale) {
        return Lang::getOrDefault($locale)->original;
    }

}