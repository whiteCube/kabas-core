<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Repeatable;

class Repeater extends Repeatable
{
      protected $type = "repeater";

      /**
       * Makes an array of defined groups
       * @param  array $value
       * @return array
       */
      protected function parse($value)
      {
            $a = [];
            if(is_array($value)){
                  $class = App::fields()->getClass('group');
                  foreach ($value as $i => $item) {
                        $a[] = App::getInstance()->make($class, [$this->getMultiFieldname($i), $item, $this]);
                  }
            }
            return $a;
      }

}
