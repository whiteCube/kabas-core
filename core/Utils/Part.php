<?php

namespace Kabas\Utils;

use \Kabas\App;
use \Kabas\View\View;

class Part
{
      /**
       * Get and display the part with the corresponding ID onto the page.
       * @param  string $partID
       * @return void
       */
      static function get($partID, $params = [])
      {
            App::config()->parts->loadPart($partID);
            $part = App::config()->parts->getPart($partID);
            $partTemplate = Text::toNamespace($part->template);

            $part = self::overrideParams($part, $params);

            $themeTemplate = '\Theme\\' . App::theme() .'\Parts\\' . $partTemplate;
            App::getInstance()->make($themeTemplate, [$part]);
      }

      static function __callStatic($method, $params)
      {
            if(!empty($params)) $params = $params[0];
            self::get($method, $params);
      }

      static function overrideParams($part, $params)
      {
            if(!empty($params)){
                  foreach($params as $key => $value){
                        if(is_object($value)) $value = (string) $value;
                        $part->data->$key = $value;
                  }
            }
            return $part;
      }
}
