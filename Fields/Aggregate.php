<?php

namespace Kabas\Fields;

use \Kabas\App;

class Aggregate extends Item implements \IteratorAggregate, \Countable 
{
      public function __toString()
      {
            return '#multiple-fields';
      }

      /**
       * Sets options & other field data
       * @return array
       */
      protected function implement($structure)
      {
            parent::implement($structure);
            if(!isset($this->reference)) $this->setOptions(@$structure->options);
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
            return $options;
      }

      /**
       * Makes item iterable
       * @return array
       */
      public function getIterator()
      {
            return new \ArrayIterator($this->output);
      }

      /**
       * Make item countable
       * @return int
       */
      public function count() {
            return count($this->output);
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
}
