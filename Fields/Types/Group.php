<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Item;

class Group extends Item implements \IteratorAggregate
{
      public $type = "group";

      public function __toString()
      {
            return '#Field-group';
      }

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
       * Condition to check if the value is correct for this field type.
       * @return bool
       */
      public function condition()
      {
            return true;
      }

      /**
       * Sets options & other field data
       * @return array
       */
      protected function implement($structure)
      {
            parent::implement($structure);
            $this->setOptions(@$structure->options);
      }

      /**
       * Overrides options list
       * @param  object $options
       * @return void
       */
      public function setOptions($options = array())
      {
            $this->options = $this->makeOptions($options);
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

      /**
       * Makes item iterable
       * @return array
       */
      public function getIterator()
      {
            return new \ArrayIterator($this->output);
      }
}
