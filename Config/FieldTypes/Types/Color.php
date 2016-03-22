<?php

namespace Kabas\Config\FieldTypes;

class Color extends Item
{
      public $type = "color";

      public function condition($value)
      {
            return gettype($value) === 'string';
      }

}
