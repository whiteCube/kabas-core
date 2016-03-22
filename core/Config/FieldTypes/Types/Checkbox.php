<?php

namespace Kabas\Config\FieldTypes;

class Checkbox extends Item
{
      public $type = "checkbox";

      public function condition($value)
      {
            return true;
      }

}
