<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Selectable;

class Radio extends Selectable
{
      protected $type = "radio";

      protected $multiple = false;

      /**
       * Sets multiple to false (always)
       * @param  boolean $value
       * @return void
       */
      public function setMultiple($value = null)
      {
            $this->multiple = false;
      }

}
