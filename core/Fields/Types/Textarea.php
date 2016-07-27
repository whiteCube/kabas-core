<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Textual;

class Textarea extends Textual
{
      public $type = "textarea";

      protected function parse($value)
      {
            return nl2br($value);
      }
}
