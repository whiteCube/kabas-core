<?php

namespace Kabas\Database\Json\Cache;

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
     * Updates identifier key for cached item
     * @param string $key
     * @return this
     */
    public function setKey($key) {
        $this->key = $key;
        return $this;
    }
}