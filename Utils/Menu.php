<?php

namespace Kabas\Utils;

use \Kabas\App;

class Menu
{
      /**
       * Get and display the menu with the corresponding ID onto the page.
       * @param  string $menu
       * @param  array $params (optionnal)
       * @return void
       */
      static function get($menu, $params = [])
      {
            $menu = App::content()->menus->get($menu);
            if($menu){
                  $menu->set($params);
                  $menu->make();
            }
      }

      static function __callStatic($method, $params)
      {
            if(!empty($params)) $params = $params[0];
            self::get($method, $params);
      }
}
