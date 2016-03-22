<?php

namespace Kabas\Config\FieldTypes;

class Wysiwyg extends Item
{
      public $type = "wysiwyg";

      public function condition($value)
      {
            return gettype($value) === 'string';
      }

}
