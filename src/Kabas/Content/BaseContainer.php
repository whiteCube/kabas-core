<?php

namespace Kabas\Content;

use Kabas\Utils\Lang;
use Kabas\Utils\File;

class BaseContainer
{
    /**
     * The loaded content items
     * @var array
     */
    protected $items;

    /**
     * path to the JSON content files
     * @var string
     */
    protected $path;

    public function __construct()
    {
        // TODO : use the defined driver in App::$driver in order to get content
        $this->path = $this->getPath();
    }

    /**
     * Loads and/or returns all items for content type
     * @return array
     */
    public function getItems()
    {
        if(is_null($this->items)) {
            $this->items = $this->loop(File::loadJsonFromDir($this->path));
        }
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }

    /**
     * Check if item exists
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        if(array_key_exists($id, $this->getItems())) return true;
        return false;
    }

    /**
     * Get item if it exists
     * @param  string $id
     * @param  mixed $lang
     * @return object
     */
    public function get($id, $lang = null)
    {
        if($this->has($id)) return $this->getItems()[$id];
        return false;
    }

    /**
     * Parses all item's fields
     * @return void
     */
    public function parse()
    {
        foreach ($this->getItems() as $item) {
            $item->parse();
        }
    }

    /**
     * Returns path to content files
     * @param null|Kabas\Config\Language $lang
     * @return string
     */
    protected function getPath($lang = null)
    {
        $lang = Lang::getOrDefault($lang);
        return CONTENT_PATH . DS . $lang->original;
    }

    /**
     * Recursively go through the files array to instanciate items
     * @param  array $files
     * @return array
     */
    protected function loop($files)
    {
        $items = [];
        foreach($files as $name => $file) {
            $file->id = $file->id ?? $this->extractNameFromFile($name);
            $file->template = $file->template ?? $this->extractNameFromFile($name);
            if(is_array($file)) $this->loop($file);
            else $items[$file->id] = $this->makeItem($file);
        }
        return $items;
    }

    protected function extractNameFromFile($filename)
    {
        $exploded = explode(DS, $filename);
        $name = str_replace('.json', '', end($exploded));
        return $name;
    }

        /**
     * Load the specified part into memory.
     * @param  string $part
     * @return object
     */
    public function load($option)
    {
        if($item = $this->get($option)) return $item;
        $item = $this->loadItem($option);
        $this->items[$option] = $this->makeItem($item);
        return $this->items[$option];
    }

    /**
     * Returns path to partial JSON file
     * @param  string $file
     * @return string
     */
    protected function getFile($file)
    {
        return realpath($this->path . DS . $file . '.json');
    }

}
