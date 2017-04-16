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
            return new Item($file);
      }

      public function getCurrent()
      {
            return $this->get(App::router()->getCurrent()->page);
      }
}
