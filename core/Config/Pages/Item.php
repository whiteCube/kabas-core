<?php

namespace Kabas\Config\Pages;

use Kabas\App;

class Item
{
      public function __construct($data)
      {
            $this->route = isset($data->route) ? $data->route : null;
            $this->id = isset($data->id) ? $data->id : null;
            $this->template = isset($data->template) ? $data->template : null;
            $this->meta = isset($data->meta) ? $data->meta : App::config()->settings->site->meta;
            $this->title = isset($data->title) ? $data->title : null;
            $this->data = isset($data->data) ? $data->data : null;
      }
}
