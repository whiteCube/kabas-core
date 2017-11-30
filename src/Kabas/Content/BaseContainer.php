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
        $this->path = $this->getPath(Lang::getCurrent());
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

    /**
     * Returns the number of items within this container
     * @return int
     */   
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
            $file = $this->checkFileIntegrity($name, $file);
            if(is_array($file)) $this->loop($file);
            else $items[$file->id] = $this->makeItem($file);
        }
        return $items;
    }

    /**
     * Ensures the file has an id and a template
     * @param string $name 
     * @param object $file 
     * @return object
     */
    protected function checkFileIntegrity($name, $file)
    {
        $file->id = $file->id ?? $this->extractNameFromFile($name);
        $file->template = $file->template ?? $this->extractNameFromFile($name);
        return $file;
    }

    /**
     * Turn file path into a name
     * @param string $filename 
     * @return string
     */
    protected function extractNameFromFile($filename)
    {
        $exploded = explode(DS, $filename);
        $name = str_replace('.json', '', end($exploded));
        return $name;
    }

    /**
     * Load the specified item into memory.
     * @param  string $item
     * @return object
     */
    public function load($item)
    {
        return $this->get($item);
    }
}
