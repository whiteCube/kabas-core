<?php

namespace Kabas\Config\Menus;

class Item
{
      public $directory = 'menus';

      public $links;

      protected function setData($data)
      {
            $this->links = isset($data->links) ? $data->links : false;
      }
}
