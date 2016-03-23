<?php

namespace Kabas\Config\FieldTypes;

class Color extends Item
{
      public $type = "color";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition($value)
      {
            return gettype($value) === 'string';
      }

}
