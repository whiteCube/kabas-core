<?php

namespace Kabas\Config\FieldTypes;

class Radio extends Item
{
      public $type = "radio";

      public function condition($value)
      {
            return true;
      }

}
