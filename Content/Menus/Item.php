<?php

namespace Kabas\Config\Menus;

class Item
{
      protected $structureDir = 'menus';

      public $options;

      public $links;

      protected function setData($data)
      {
            $this->links = isset($data->links) ? $data->links : false;
            $this->options = isset($data->options) ? $data->options : false;
      }
}
