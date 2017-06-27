<?php

namespace Kabas\Content;

use \Kabas\Utils\Lang;
use \Kabas\Utils\File;

class BaseContainer
{
    public $items = [];

    /**
     * path to the JSON content files
     * @var string
     */
    protected $path;

    public function __construct()
    {
        // TODO : use the defined driver in App::$driver in order to get content
        $this->path = $this->getPath();
        $this->loop(File::loadJsonFromDir($this->path));
    }

    /**
     * Check if item exists
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        if(array_key_exists($id, $this->items)) return true;
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
        if($this->has($id)) return $this->items[$id];
        return false;
    }

    /**
     * Parses all item's fields
     * @return void
     */
    public function parse()
    {
        foreach ($this->items as $item) {
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
     * @return void
     */
    protected function loop($files)
    {
        foreach($files as $name => $file) {
            $file->id = isset($file->id) ? $file->id : $this->extractNameFromFile($name);
            $file->template = isset($file->template) ? $file->template : $this->extractNameFromFile($name);
            if(is_array($file)) $this->loop($file);
            else $this->items[$file->id] = $this->makeItem($file);
        }
    }

    protected function extractNameFromFile($filename)
    {
        $exploded = explode(DS, $filename);
        $name = str_replace('.json', '', end($exploded));
        return $name;
    }
    
}
