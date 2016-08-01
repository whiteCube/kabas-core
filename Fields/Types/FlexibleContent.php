<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Groupable;

class FlexibleContent extends Groupable
{
      public $type = "flexiblecontent";

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
                  if($group = $this->getStructure($item->option)){
                        $a[] = App::getInstance()->make($class, [$this->name . '_' . $i, $item->value, $group]);
                  }
            }
            return $a;
      }

      protected function getStructure($key)
      {
            if(isset($this->options->$key)) {
                  $group = new \stdClass();
                  $group->option = $key;
                  $group->options = $this->options->$key;
                  return $group;
            }
            return false;
      }

}
