<?php 

namespace Kabas\Objects\Uploads;

use Kabas\Exceptions\NotFoundException;

class Container {

    /**
     * A collection of Kabas\Objects\Uploads\Item
     * @var array
     */
    protected $items = [];

    public function __construct()
    {
        $this->load();
    }

    /**
     * Static accessor for get method
     * @param string $name 
     * @param array $args 
     * @return Kabas\Objects\Uploads\Item
     */
    public function __call($name, $args)
    {
        return $this->get($name);
    }

    /**
     * Creates instances of Kabas\Objects\Uploads\Item for each upload in $_FILES
     * @return void
     */
    protected function load()
    {
        foreach($_FILES as $key => $file) {
            $this->items[$key] = new Item($key, $file, new UploadMover);
        }
    }

    /**
     * Gets an upload item instance
     * @param string $key 
     * @throws NotFoundException if $key is not a currently accessible upload
     * @return Kabas\Objects\Uploads\Item
     */
    public function get($key)
    {
        if(!$this->has($key)) throw new NotFoundException($key, 'upload');
        return $this->items[$key];
    }

    /**
     * Checks if $key exists in uploads
     * @param string $key 
     * @return bool
     */
    public function has($key)
    {
        return isset($this->items[$key]);
    }

}