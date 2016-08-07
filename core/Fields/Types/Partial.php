<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Groupable;

class Partial extends Groupable
{
      protected $type = "partial";

      protected $reference;

      /**
       * includes referenced partial's view with its data
       * @param  string $option
       * @return void
       */
      public function render()
      {
            if($this->reference){
                  $this->reference->set($this->output);
                  $this->reference->make();
            }
            return $this;
      }

      /**
       * makes options from user defined list
       * @param  string $options
       * @return array
       */
      protected function makeOptions($options)
      {
            $a = [];
            if($fields = $this->getPartFields($options)){
                  foreach($fields as $name => $field){
                        $a[$name] = $field;
                  }
            }
            return $a;
      }

      /**
       * Gets requested partial's fields
       * @param  string $part
       * @return array
       */
      protected function getPartFields($part)
      {
            $part = App::content()->partials->load($part);
            if($part){
                  $this->reference = $part;
                  if(is_object($part->fields)) return $part->fields;
                  return [];
            }
            return false;
      }

      /**
       * Makes an array of defined fields
       * @param  object $value
       * @return array
       */
      protected function parse($value)
      {
            $a = [];
            foreach ($this->options as $key => $field) {
                  $val = isset($value->$key) ? $value->$key : null;
                  $a[$key] = clone $field;
                  $a[$key]->set($val);
            }
            return $a;
      }
}
