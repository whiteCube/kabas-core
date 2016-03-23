<?php

namespace Kabas\Config\FieldTypes;

class Repeater extends Item
{
      public $type = "repeater";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition($value)
      {
            return true;
      }

}
