<?php

namespace Kabas\Content\Options;

use Kabas\App;
use Kabas\Utils\File;
use Kabas\Utils\Text;
use Kabas\Content\BaseContainer;

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

    protected function makeItem($file)
    {
        return new Item($file);
    }

}
