<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Selectable;

class Checkbox extends Selectable
{
      public $type = "checkbox";
      protected $allowsMultipleValues = true;
}
