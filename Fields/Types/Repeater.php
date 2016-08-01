<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Groupable;

class Repeater extends Groupable
{
      public $type = "repeater";

      protected $multiple = true;

      /**
       * Makes an array of defined groups
       * @param  array $value
       * @return array
       */
      protected function parse($value)
      {
            $a = [];
            $class = App::fields()->getClass('group');
            foreach ($value as $i => $item) {
                  $a[] = App::getInstance()->make($class, [$this->name . '_' . $i, $item, $this]);
            }
            return $a;
      }

}
