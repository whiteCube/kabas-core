<?php

namespace Kabas\Config\Parts;

class Item
{
      public function __construct($data)
      {
            $this->id = $data->id;
            $this->template = $data->template;
            $this->options = $data->options;
            $this->data = $data->data;
      }
}
