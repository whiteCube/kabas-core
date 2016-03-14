<?php

namespace Kabas\Utils;

use \Kabas\Kabas;
use \Kabas\View\View;

class Part
{
      static function get($partID)
      {
            $app = Kabas::getInstance();

            $part = $app->config->parts->getPart($partID);

            View::make($part->template, $part->data);

      }
}
