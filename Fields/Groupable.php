<?php

namespace Kabas\Fields;

use \Kabas\App;

class Groupable extends Aggregate
{
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
            return is_object($this->value);
      }
}
