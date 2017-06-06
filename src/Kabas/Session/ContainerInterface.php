<?php

namespace Kabas\Session;

interface ContainerInterface {

    public function set(string $key, $value);

    public function get(string $key);
    
    public function has(string $key) : bool;

    public function forget(string $key);

    public function flush();

    public function extract() : array;

}
