<?php

namespace Kabas\Config\FieldTypes;

class Image extends Item
{
      public $type = "image";

      public function condition($value)
      {
            return true;
      }

}
