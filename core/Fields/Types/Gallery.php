<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Groupable;

class Gallery extends Groupable
{
      public $type = "gallery";

      protected $multiple = true;

      /**
       * Returns options (not needed on this type)
       * @return array
       */
      protected function makeOptions($options)
      {
            return $options;
      }

      /**
       * Makes an array of images
       * @param  array $value
       * @return array
       */
      protected function parse($value)
      {
            $a = [];
            $class = App::fields()->getClass('image');
            foreach ($value as $i => $item) {
                  $a[] = App::getInstance()->make($class, [$this->name . '_' . $i, $item, $this]);
            }
            return $a;
      }
}
