<?php

namespace Kabas\Config\Pages;

use Kabas\App;

class Item
{
      public function __construct($data)
      {
            $app = App::getInstance();
            $this->route = isset($data->route) ? $data->route : '';
            $this->id = isset($data->id) ? $data->id : '';
            $this->template = isset($data->template) ? $data->template : '';
            $this->meta = isset($data->meta) ? $data->meta : $app->config->settings->site->meta;
            $this->title = isset($data->title) ? $data->title : '';
            $this->data = isset($data->data) ? $data->data : '';
      }
}
