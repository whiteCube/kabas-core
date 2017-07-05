<?php

namespace Kabas\Content\Options;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Utils\Text;
use \Kabas\Content\BaseContainer;

class Container extends BaseContainer
{

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
     * Returns path to optionials directory
     * @return string
     */
    protected function getPath($lang = null)
    {
        return parent::getPath($lang) . DS . 'options';
    }

    /**
     * Returns path to option JSON file
     * @param  string $file
     * @return string
     */
    protected function getFile($file)
    {
        return realpath($this->path . DS . $file . '.json');
    }

    protected function makeItem($file)
    {
        return new Item($file);
    }

    //    TODO :
    //    All the following should move to BaseItem
    //    and be supported on each content type.

    protected function loadItem($id)
    {
        $file = $this->getFile($id);
        if($file) return $this->loadFromContent($file);
        // Not found.
        throw new \Kabas\Exceptions\NotFoundException($id, 'option');
    }

    protected function loadFromContent($file)
    {
        $file = File::loadJson($file);
        return $file;
    }

}
