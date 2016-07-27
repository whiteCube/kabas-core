<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Textual;

class Email extends Textual
{
      public $type = "email";

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            if(!parent::condition()) return false;
            return filter_var($this->value, FILTER_VALIDATE_EMAIL);
      }
}
