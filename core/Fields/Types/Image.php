<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Item;

class Image extends Item
{
      public $type = "image";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return true;
      }

      public function __call($name, $args)
      {
            return call_user_func_array([$this->output, $name], $args);
      }

      /**
       * Makes an Image instance from value
       * @param  mixed $value
       * @return object
       */
      protected function parse($value)
      {
            return App::getInstance()->make('\Kabas\Objects\Image\Item', [$value]);
      }

}
