<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Textual;

class Textarea extends Textual
{
      protected $type = "textarea";

      protected function parse($value)
      {
            return nl2br($value);
      }
}
