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
      public function condition()
      {
            return (gettype($this->data) === 'string' && filter_var($this->data, FILTER_VALIDATE_EMAIL));
      }
      // TODO: parse
}
