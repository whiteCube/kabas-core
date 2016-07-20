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

      /**
       * Check if part exists in content
       * @param  string  $partID
       * @return boolean
       */
      public function hasMenu($menuID)
      {
            if(array_key_exists($menuID, $this->items)) return true;
            return false;
      }

      /**
       * Get part if it exists
       * @param  string $partID
       * @return object
       */
      public function getMenu($menuID)
      {
            if($this->hasMenu($menuID)) return $this->items[$menuID];
            else return 'error, menu does not exist';
      }

}
