<?php

namespace Kabas\Controller;

use Kabas\Utils\Url;
use Kabas\App;

class MenuItem
{
      public function __construct($id, $data)
      {
            $this->url = Url::to($id);
            foreach($data as $key => $value) {
                  $this->$key = $value;
            }
            if(!isset($this->label)) {
                  $this->label = App::config()->pages->items[$id]->title;
            }
      }

      /**
       * Check if menu item has a submenu
       * @return boolean
       */
      public function hasSubmenu()
      {
            if(isset($this->subitems)) return true;
            return false;
      }
}
