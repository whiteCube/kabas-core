<?php

namespace Kabas\Content\Options;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Utils\Text;
use \Kabas\Content\BaseContainer;

class Container extends BaseContainer
{

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

}
