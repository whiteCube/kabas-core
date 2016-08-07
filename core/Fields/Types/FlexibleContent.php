<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Repeatable;

class FlexibleContent extends Repeatable
{
      public $type = "flexiblecontent";

      /**
       * makes options from user defined list
       * @return array
       */
      protected function makeOptions($options)
      {
            $a = [];
            foreach(parent::makeOptions($options) as $key => $field){
                  $item = new \stdClass();
                  $item->class = App::fields()->getClass(isset($field->type) ? $field->type : 'text');
                  $item->structure = $field;
                  $item->structure->option = $key;
                  $a[] = $item;
            }
            return $a;
      }

      /**
       * Makes an array of defined groups
       * @param  array $value
       * @return array
       */
      protected function parse($value)
      {
            $a = [];
            foreach ($value as $i => $item) {
                  if($option = $this->getOption($item->option)){
                        $a[] = App::getInstance()->make($option->class, [$this->getMultiFieldname($i), $item->value, $option->structure]);
                  }
            }
            return $a;
      }

      protected function getOption($key)
      {
            foreach ($this->options as $option) {
                  if($option->structure->option === $key) return $option;
            }
            return false;
      }

}
