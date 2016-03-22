<?php

namespace Kabas\Config\FieldTypes;

class Repeater extends Item
{
      public $type = "repeater";

      public function condition($value)
      {
            return true;
      }

}
