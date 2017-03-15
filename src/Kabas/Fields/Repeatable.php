<?php

namespace Kabas\Fields;

use \Kabas\App;

class Repeatable extends Aggregate
{
      protected $multiple = true;

      /**
       * Formats a raw value in order and makes it usable for said field type
       * @param mixed $value
       * @return string
       */
      public static function format($value)
      {
          if(is_string($value) && !strlen($value)) return [];
          if(is_array($value = parent::format($value))) return $value;
          return false;
      }

      /**
       * Condition to check if the value is correct for this field type.
       * @return bool
       */
      public function condition()
      {
            return is_array($this->value);
      }

      /**
       * makes an unique field name for repeatable fields
       * @param  int $i
       * @return string
       */
      protected function getMultiFieldname($i)
      {
            return $this->name . '_' . $i;
      }
}
