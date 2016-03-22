<?php

namespace Kabas\Config\FieldTypes;

class Url extends Item
{
      public $type = "url";

      public function condition($value)
      {
            return filter_var($value, FILTER_VALIDATE_URL);
      }

}
