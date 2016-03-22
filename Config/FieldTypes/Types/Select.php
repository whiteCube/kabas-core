<?php

namespace Kabas\Config\FieldTypes;

class Select extends Item
{
      public $type = "select";

      public function condition($value)
      {
            return true;
      }

}
