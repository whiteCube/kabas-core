<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Item;

class Repeater extends Item
{
      public $type = "repeater";

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
