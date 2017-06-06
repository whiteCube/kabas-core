<?php

namespace Kabas\Session;

class FlashContainer implements ContainerInterface
{
    /**
     * The flash data from the previous request
     * @var array
     */
    private $old = [];

    /**
     * The flash data for the next request
     * @var array
     */
    private $new = [];

    /**
     * Creates an instance and stores previously fetched data
     * @param array $data 
     * @return void
     */
    public function __construct(array $data)
    {
        $this->old = $data;
    }

    /**
     * Sets a key/value pairing into the flash session
     * (will be deleted automatically after the next request)
     * @param string $key 
     * @param mixed $value 
     * @return void
     */
    public function set(string $key, $value)
    {
        $this->new[$key] = $value;
    }

    /**
     * Gets $key from the flash session
     * @param string $key 
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->old[$key] ?? $this->new[$key] ?? null;
    }

    /**
     * Gets $key from the flash session and then deletes it
     * @param string $key 
     * @return mixed
     */
    public function pull(string $key)
    {
        $value = $this->get($key);
        $this->forget($key);
        return $value;
    }

    /**
     * Checks if flash session contains $key
     * @param string $key 
     * @return bool
     */
    public function has(string $key) : bool
    {
        return isset($this->new[$key]) ? true : isset($this->old[$key]);
    }

    /**
     * Deletes $key from the flash session
     * @param string $key 
     * @return void
     */
    public function forget(string $key)
    {
        if(isset($this->old[$key])) unset($this->old[$key]);
        if(isset($this->new[$key])) unset($this->new[$key]);
    }

    /**
     * Keeps the specified data in flash session for one additional request
     * Accepts a single key or an array of keys as a parameter
     * @param string|array $keys 
     * @return void
     */
    public function again($keys)
    {
        if(!is_array($keys)) $keys = [$keys];
        foreach ($keys as $key) {
            if(!isset($this->old[$key])) continue;
            $this->new[$key] = $this->old[$key];
        }
    }

    /**
     * Keeps all of the data in flash session for one additional request
     * @return void
     */
    public function reflash()
    {
        $this->new = array_merge($this->new, $this->old);
    }

    /**
     * Forgets all data in the flash session
     * @return void
     */
    public function flush()
    {
        $this->new = [];
    }

    /**
     * Gets all data stored in the flash session
     * @return array
     */
    public function extract() : array
    {
        return $this->new;
    }
}
