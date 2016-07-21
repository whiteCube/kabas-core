<?php

namespace Kabas\Content\Pages;

use \Kabas\App;
use \Kabas\Content\BaseItem;

class Item extends BaseItem
{
      protected $structureDir = 'templates';

      public $route;

      public $meta;

      public $title;

      protected function setData($data)
      {
            $this->route = isset($data->route) ? $data->route : false;
            $this->title = isset($data->title) ? $data->title : false;
            $this->meta = $this->getMeta($data);
      }

      protected function getMeta($data)
      {
            // TODO : merge default site meta with given meta
            return isset($data->meta) ? $data->meta : App::config()->settings->site->meta;
      }
}
