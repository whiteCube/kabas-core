<?php

namespace Kabas\Config\FieldTypes;

class Number extends Item
{
      public $type = "number";

      public function condition($value)
      {
            return gettype($value) === 'integer';
      }

}
