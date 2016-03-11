<?php

namespace Kabas\Config\Pages;

class Item
{
      public function __construct($data)
      {
            $this->route = $data->route;
            $this->id = $data->id;
            $this->template = $data->template;
            $this->data = $data->data;
      }
}
