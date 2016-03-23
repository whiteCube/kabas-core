<?php

namespace Kabas\Config\FieldTypes;

class Url extends Item
{
      public $type = "url";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition($value)
      {
            return filter_var($value, FILTER_VALIDATE_URL);
      }

}
