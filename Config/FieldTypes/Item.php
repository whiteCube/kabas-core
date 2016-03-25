<?php

namespace Kabas\Config\FieldTypes;

class Item
{
      public $type;
      public $fieldName;
      public $data;

      public function __construct($fieldName = null, $data = null)
      {
            $this->fieldName = $fieldName;
            $this->data = $data;

            if(isset($this->fieldName) && isset($this->data)) {
                  try { $this->check($fieldName, $this->data); }
                  catch (\Kabas\Exceptions\TypeException $e) {
                        echo $e->getMessage();
                  }
            }
      }

      public function __toString()
      {
            return $this->data;
      }

      public function __call($name, $arguments)
      {
            if(!method_exists($this, $name)) {
                  echo '<pre>Error: Method "' . $name . '" does not exist for field type → "' . $this->type .'"</pre>';
            }
      }

      /**
       * Runs the condition and throw an error if it returns false.
       * @param  string $field
       * @param  mixed $value
       * @return void
       */
      public function check($field, $value)
      {
            if(!$this->condition($value)) {
                  $error = 'Field "' . $field . '" has an incorrect value → type "' . $this->type . '".';
                  throw new \Kabas\Exceptions\TypeException($error);
            }
      }
}
