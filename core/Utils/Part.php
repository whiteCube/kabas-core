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

            View::make($part->template, $part->data);

      }
}
