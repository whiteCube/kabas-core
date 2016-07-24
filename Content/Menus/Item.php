<?php

namespace Kabas\Content\Menus;

use \Kabas\Content\BaseItem;

class Item extends BaseItem
{
      public $directory = 'menus';

      public $items;

      protected function setData($data)
      {
            $this->items = isset($data->items) ? $data->items : false;
      }
}
