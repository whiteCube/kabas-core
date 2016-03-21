<?php

namespace Kabas\Utils;

use \Kabas\App;

class Menu
{
      /**
       * Get and display the menu with the corresponding ID onto the page.
       * @param  string $menuID
       * @return void
       */
      static function get($menuID)
      {
            $menu = App::config()->menus->getMenu($menuID);
            $menuTemplate = Text::toNamespace($menu->template);
            $themeTemplate = '\Theme\\' . App::config()->settings->site->theme .'\Menus\\' . $menuTemplate;
            new $themeTemplate($menu->template, $menu->links, $menu->options);
      }
}
