<?php

namespace Kabas\Config\FieldTypes;

class Textarea extends Item
{
      public $type = "textarea";

      public function condition($value)
      {
            return gettype($value) === 'string';
      }

}
