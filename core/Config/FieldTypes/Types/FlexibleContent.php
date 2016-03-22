<?php

namespace Kabas\Config\FieldTypes;

class FlexibleContent extends Item
{
      public $type = "flexible-content";

      public function condition($value)
      {
            return true;
      }

}
