<?php

namespace Kabas\Config\Menus;

class Item
{
      public function __construct($data)
      {
            $this->id         = isset($data->id) ? $data->id : null;
            $this->template   = isset($data->template) ? $data->template : null;
            $this->links      = isset($data->links) ? $data->links : null;
            $this->options    = isset($data->options) ? $data->options : null;
      }
}
