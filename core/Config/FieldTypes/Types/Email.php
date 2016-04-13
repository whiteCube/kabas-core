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

      public function parse()
      {
            $parts = explode('@', $this->data);
            $o = new \stdClass();
            $o->local = $parts[0];
            $o->domain = $parts[1];
            return $o;
      }
}
