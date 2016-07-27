<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Item;

class File extends Item
{
      public $type = "file";

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
