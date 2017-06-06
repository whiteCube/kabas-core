<?php

namespace Kabas\Session;

class DataContainer implements ContainerInterface
{
    private $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function has(string $key) : bool
    {
        return isset($this->data[$key]);
    }

    public function forget(string $key)
    {
        if(isset($this->data[$key])) unset($this->data[$key]);
    }

    public function flush()
    {
        $this->data = [];
    }

    public function extract() : array
    {
        return $this->data;
    }
}
