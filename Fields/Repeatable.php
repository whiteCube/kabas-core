<?php

namespace Kabas\Fields;

use \Kabas\App;

class Repeatable extends Aggregate
{
      protected $multiple = true;

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
