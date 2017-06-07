<?php

namespace Kabas\Session;

interface ContainerInterface {

    /**
     * Sets a key/value pairing into the session
     * @param string $key 
     * @param mixed $value 
     * @return void
     */
    public function set(string $key, $value);

    /**
     * Gets $key from the session
     * @param string $key 
     * @return mixed
     */
    public function get(string $key);

    /**
     * Gets $key from the session and deletes it
     * @param string $key 
     * @return void
     */
    public function pull(string $key);
    
    /**
     * Checks if session contains $key
     * @param string $key 
     * @return bool
     */
    public function has(string $key) : bool;

    /**
     * Deletes $key from the session
     * @param string $key 
     * @return type
     */
    public function forget(string $key);

    /**
     * Forgets all data in the session
     * @return void
     */
    public function flush();

    /**
     * Gets all data stored in the session
     * @return array
     */
    public function extract() : array;

}
