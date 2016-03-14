<?php

namespace Kabas\Utils;

use \Kabas\App;
use \Kabas\View\View;

class Part
{
      static function get($partID)
      {
            $app = App::getInstance();

            $part = $app->config->parts->getPart($partID);
            $partTemplate = Text::toNamespace($part->template);

            $themeTemplate = '\Theme\\' . $app->config->settings->site->theme .'\Parts\\' . $partTemplate;
            new $themeTemplate($part->template, $part->data, $part->options);
      }

      static function header()
      {
            self::get('header');
      }

      static function footer()
      {
            self::get('footer');
      }
}
