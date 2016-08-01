<?php

namespace Kabas\Fields;

use \Kabas\App;

class Groupable extends Item implements \IteratorAggregate
{
      public function __toString()
      {
            return '#multiple-fields';
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
       * Makes item iterable
       * @return array
       */
      public function getIterator()
      {
            return new \ArrayIterator($this->output);
      }
}
