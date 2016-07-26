<?php

namespace Kabas\FieldTypes;

use \Carbon\Carbon;

class Date extends Item
{
      public $type = "date";

      public function __construct($fieldName = null, $data = null)
      {
            parent::__construct($fieldName, $data);
            $this->data = new Carbon($data);
      }

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return strtotime($this->data);
      }

      public function __get($key)
      {
            return $this->data->$key;
      }

      public function __set($key, $value)
      {
            $this->data->$key = $value;
            return $this->data;
      }

      public function __call($method, $params)
      {
            return call_user_func_array([$this->data, $method], $params);
      }

      public function __toString()
      {
            return $this->data->__toString();
      }

}
