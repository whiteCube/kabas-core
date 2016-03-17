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
            $app = App::getInstance();

            $part = $app->config->parts->getPart($partID);
            $partTemplate = Text::toNamespace($part->template);

            $themeTemplate = '\Theme\\' . $app->config->settings->site->theme .'\Parts\\' . $partTemplate;
            new $themeTemplate($partID, $part->template, $part->data, $part->options);
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
