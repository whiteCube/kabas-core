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
            $menu->set($params);
            $controller = '\Theme\\' . App::theme() .'\Menus\\' . Text::toNamespace($menu->template);
            if(!class_exists($controller)) $controller = \Kabas\controller\MenuController::class;
            App::getInstance()->make($controller, [$menu]);
      }

      static function __callStatic($method, $params)
      {
            if(!empty($params)) $params = $params[0];
            self::get($method, $params);
      }
}
