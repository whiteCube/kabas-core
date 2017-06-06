<?php

namespace Kabas\Session;

class FlashContainer implements ContainerInterface
{
    private $old = [];

    private $new = [];

    public function __construct(array $data)
    {
        $this->old = $data;
    }

    public function set(string $key, $value)
    {
        $this->new[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->old[$key] ?? $this->new[$key] ?? null;
    }

    public function has(string $key) : bool
    {
        return isset($this->new[$key]) ? true : isset($this->old[$key]);
    }

    public function forget(string $key)
    {
        if(isset($this->old[$key])) unset($this->old[$key]);
        if(isset($this->new[$key])) unset($this->new[$key]);
    }

    public function again(string $key)
    {
        if(!isset($this->old[$key])) return;
        $this->new[$key] = $this->old[$key];
    }

    public function reflash()
    {
        $this->new = array_merge($this->new, $this->old);
    }

    public function flush()
    {
        $this->new = [];
    }

    public function extract() : array
    {
        return $this->new;
    }
}
