<?php

namespace Kabas\Session;

class DataContainer implements ContainerInterface
{
    private $data = [];

    /**
     * Creates an instance and stores previously fetched dara
     * @param array $data 
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Sets a key/value pairing into the session
     * @param string $key 
     * @param mixed $value 
     * @return void
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Gets $key from the session
     * @param string $key 
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Gets $key from the session and then deletes it
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
     * Checks if session contains $key
     * @param string $key 
     * @return bool
     */
    public function has(string $key) : bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Deletes $key from the session
     * @param string $key 
     * @return void
     */
    public function forget(string $key)
    {
        if(isset($this->data[$key])) unset($this->data[$key]);
    }

    /**
     * Forgets all data in the session (not including flash messages)
     * @return void
     */
    public function flush()
    {
        $this->data = [];
    }

    /**
     * Gets all data stored in the session
     * @return array
     */
    public function extract() : array
    {
        return $this->data;
    }
}
