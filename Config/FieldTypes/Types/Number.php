<?php

namespace Kabas\Config\FieldTypes;

class Number extends Item
{
      public $type = "number";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition($value)
      {
            return gettype($value) === 'integer';
      }

}
