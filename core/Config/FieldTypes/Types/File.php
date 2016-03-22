<?php

namespace Kabas\Config\FieldTypes;

class File extends Item
{
      public $type = "file";

      public function condition($value)
      {
            return true;
      }

}
