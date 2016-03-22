<?php

namespace Kabas\Config\FieldTypes;

class Email extends Item
{
      public $type = "email";

      public function condition($value)
      {
            return (gettype($value) === 'string' && filter_var($value, FILTER_VALIDATE_EMAIL));
      }

}
