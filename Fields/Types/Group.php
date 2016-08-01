<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Groupable;

class Group extends Groupable
{
      public $type = "group";

      public function __get($name)
      {
            return $this->get($name);
      }

      public function __call($name, $params)
      {
            return $this->get($name);
      }

      /**
       * Retrieves one of the contained fields
       * @param  string $name
       * @return object
       */
      public function get($key)
      {
            if(isset($this->output[$key])) return $this->output[$key];
            return null;
      }

      /**
       * makes options from user defined list
       * @return array
       */
      protected function makeOptions($options)
      {
            if(!is_array($options) && !is_object($options)) throw new \Exception('Field groups require a valid fields list.');
            $opts = [];
            foreach($options as $name => $field){
                  $class = 
                  $item = new \stdClass();
                  $item->name = $name;
                  $item->class = App::fields()->getClass(isset($field->type) ? $field->type : 'text');
                  $item->structure = $field;
                  $opts[] = $item;
            }
            return $opts;
      }

      /**
       * Makes an array of defined fields
       * @param  object $value
       * @return array
       */
      protected function parse($value)
      {
            $a = [];
            foreach ($this->options as $field) {
                  if($field->class){
                        $key = $field->name;
                        $val = isset($value->$key) ? $value->$key : null;
                        $a[$key] = App::getInstance()->make($field->class, [$key, $val, $field->structure]);
                  }
            }
            return $a;
      }
}
