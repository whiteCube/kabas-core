<?php

namespace Kabas\Fields;

use \Kabas\App;

class Groupable extends Aggregate
{
      public function __set($name, $value)
      {
            if(isset($this->options[$name])) $this->output[$name]->set($value);
            else $this->output[$name] = $value;
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
       * Condition to check if the value is correct for this field type.
       * @return bool
       */
      public function condition()
      {
            return is_object($this->value);
      }
}
