<?php

namespace Kabas\Database\Json\Cache;

class Item
{
    /**
    * Identifier
    * @var string
    */
    protected $key;

    /**
    * contained data
    * @var mixed
    */
    protected $data;

    /**
    * Item's cache namespace
    * @var \Kabas\Database\Json\Cache\Namespace
    */
    protected $namespace;

    /**
     * Create a new cache item instance
     * @param string $name
     * @param string $classname
     * @param bool   $translatable
     * @return void
     */
    public function __construct(Namespace $namespace) {
        $this->namespace = $namespace;
    }

    /**
     * Updates data for cached item
     * @param mixed  $data
     * @return void
     */
    public function set($data) {
        $this->data = $data;
    }

    /**
     * Updates identifier key for cached item
     * @param string $key
     * @return void
     */
    public function setKey($key) {
        $this->key = $key;
    }
}