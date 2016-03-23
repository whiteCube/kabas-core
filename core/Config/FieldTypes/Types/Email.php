<?php

namespace Kabas\Config\FieldTypes;

class Email extends Item
{
      public $type = "email";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition($value)
      {
            return (gettype($value) === 'string' && filter_var($value, FILTER_VALIDATE_EMAIL));
      }

}
