<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Item;

class Gallery extends Item
{
      public $type = "gallery";

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
