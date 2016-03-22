<?php

namespace Kabas\Config\FieldTypes;

class Item
{
      public $type;

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
