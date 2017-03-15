<?php

namespace Kabas\Model;

class Item
{
      public function __construct($data)
      {
            $this->id = isset($data->id) ? $data->id : null;
            $this->template = isset($data->template) ? $data->template : null;
            $this->options = isset($data->options) ? $data->options : null;
            $this->data = isset($data->data) ? $data->data : null;
      }
}
