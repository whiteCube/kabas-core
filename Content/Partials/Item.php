<?php

namespace Kabas\Content\Partials;

use \Kabas\Content\BaseItem;

class Item extends BaseItem
{
      protected $structureDir = 'partials';

      public $options;

      protected function setData($data)
      {
            $this->options = isset($data->options) ? $data->options : false;
      }
}
