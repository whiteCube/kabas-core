<?php

namespace Kabas\Config\FieldTypes;

class Text extends Item
{
      public $type = "text";

      public function condition($value)
      {
            return gettype($value) === 'string';
      }

}
