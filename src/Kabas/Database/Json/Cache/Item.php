<?php

namespace Kabas\Database\Json\Cache;

use Kabas\Utils\File;

class Item
{
    /**
    * Identifier
    * @var string
    */
    public $key;

    /**
    * contained data
    * @var mixed
    */
    public $data;

    /**
    * path to item's file
    * @var mixed
    */
    public $path;

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
     * Updates data for cached item
     * @param mixed  $data
     * @return this
     */
    public function set($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * Updates file path for cached item
     * @param string $path
     * @return this
     */
    public function setPath($path) {
        $this->path = $path;
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
     * @return this
     */
    public function load() {
        if(is_null($content = File::loadJsonIfValid($this->path, false))) return $this->set(false);
        return $this->set($content);
    }

    /**
     * Transforms this item to stdClass
     * @param string $key
     * @return \stdClass
     */
    public function toDataObject($key) {
        if(is_null($this->data)) $this->load();
        $item = $this->getDataAsObject();
        $item->{$key} = $this->key;
        return $item;
    }

    protected function getDataAsObject() {
        if(is_object($this->data)) return $this->data;
        if(is_array($this->data)) return (object) $this->data;
        $data = new \stdClass;
        $data->value = $this->data;
        return $data;
    }
    
}