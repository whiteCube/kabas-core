<?php

namespace Kabas\Utils;

use \Kabas\App;

class Part
{
      /**
       * Get and display the part with the corresponding ID onto the page.
       * @param  string $part
       * @param  array $params (optionnal)
       * @return void
       */
      static function get($part, $params = [])
      {
            $part = App::content()->partials->load($part);
            $part->set($params);
            $controller = '\Theme\\' . App::theme() .'\Partials\\' . Text::toNamespace($part->template);
            if(!class_exists($controller)) $controller = \Kabas\Controller\PartialController::class;
            App::getInstance()->make($controller, [$part]);
      }

      static function __callStatic($method, $params)
      {
            if(!empty($params)) $params = $params[0];
            self::get($method, $params);
      }
}
