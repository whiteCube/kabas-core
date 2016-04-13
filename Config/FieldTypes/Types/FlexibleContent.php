<?php

namespace Kabas\Config\FieldTypes;

class FlexibleContent extends Item
{
      public $type = "flexible-content";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return true;
      }

}
