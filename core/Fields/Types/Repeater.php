<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Groupable;

class Repeater extends Groupable
{
      public $type = "repeater";

      protected $multiple = true;

      /**
       * Condition to check if the value is correct for this field type.
       * @return bool
       */
      public function condition()
      {
            return is_array($this->value);
      }

      /**
       * makes options from user defined list
       * @return array
       */
      protected function makeOptions($options)
      {
            if(!is_array($options) && !is_object($options)) throw new \Exception('Field groups require a valid fields list.');
            return $options;
      }

      /**
       * Makes an array of defined arrays
       * @param  array $value
       * @return array
       */
      protected function parse($value)
      {
            $a = [];
            $group = App::fields()->getClass('group');
            foreach ($value as $i => $item) {
                  $a[] = App::getInstance()->make($group, [$i, $item, $this]);
            }
            return $a;
      }

}
