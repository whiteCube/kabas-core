<?php

namespace Kabas\Config\FieldTypes;

class Gallery extends Item
{
      public $type = "gallery";

      public function condition($value)
      {
            return true;
      }

}
