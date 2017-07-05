<?php

namespace Kabas\Content\Options;

use \Kabas\App;
use \Kabas\Content\BaseItem;

class Item extends BaseItem
{
    public $directory = 'options';

    public function setData($data)
    {
        $this->set($data);
    }
}
