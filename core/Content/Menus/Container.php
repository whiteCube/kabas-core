<?php

namespace Kabas\Content\Menus;

use \Kabas\App;
use \Kabas\Content\BaseContainer;

class Container extends BaseContainer
{
      protected function getPath()
      {
            return parent::getPath() . DS . 'menus';
      }

      protected function makeItem($file)
      {
            return App::getInstance()->make('\Kabas\Content\Menus\Item', [$file]);
      }

}
