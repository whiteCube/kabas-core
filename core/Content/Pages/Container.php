<?php

namespace Kabas\Content\Pages;

use \Kabas\App;
use \Kabas\Content\BaseContainer;

class Container extends BaseContainer
{
      protected function getPath()
      {
            return parent::getPath() . DS . 'pages';
      }

      protected function makeItem($file)
      {
            return App::getInstance()->make('\Kabas\Content\Pages\Item', [$file]);
      }

      public function getPage($id)
      {
            if(array_key_exists($id, $this->items)) return $this->items[$id];
            return false;
      }

      public function getCurrent()
      {
            return $this->getPage(App::router()->getCurrent()->page);
      }

}
