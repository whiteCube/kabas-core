<?php

namespace Kabas\Utils;

use \Kabas\App;
use \Kabas\View\View;

class Part
{
      /**
       * Get and display the menu with the corresponding ID onto the page.
       * @param  string $partID
       * @return void
       */
      static function get($partID)
      {
            App::config()->parts->loadPart($partID);
            $part = App::config()->parts->getPart($partID);
            $partTemplate = Text::toNamespace($part->template);

            $themeTemplate = '\Theme\\' . App::config()->settings->site->theme .'\Parts\\' . $partTemplate;
            // var_dump($themeTemplate);
            App::getInstance()->make($themeTemplate, [$part]);
      }

      /**
       * Get and display the site's header
       * @return void
       */
      static function header()
      {
            self::get('header');
      }

      /**
       * Get and display the site's footer
       * @return void
       */
      static function footer()
      {
            self::get('footer');
      }
}
