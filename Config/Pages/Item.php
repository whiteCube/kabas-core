<?php

namespace Kabas\Config\Pages;

class Item
{
      public function __construct($data)
      {
            $this->route = isset($data->route) ? $data->route : '';
            $this->id = isset($data->id) ? $data->id : '';
            $this->template = isset($data->template) ? $data->template : '';
            $this->title = isset($data->title) ? $data->title : '';
            $this->data = isset($data->data) ? $data->data : '';
      }
}
