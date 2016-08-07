<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Selectable;

class Checkbox extends Selectable
{
      protected $type = "checkbox";

      protected $multiple = true;

      /**
       * Sets multiple to true (always)
       * @param  boolean $value
       * @return void
       */
      public function setMultiple($value = null)
      {
            $this->multiple = true;
      }
}
