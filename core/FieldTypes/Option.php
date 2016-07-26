<?php

namespace Kabas\FieldTypes;

class Option extends Item
{
      public $type = "option";

      public function __construct($option)
      {
            $this->fieldName = $option->id;
            $this->data = $option;

            if(isset($fieldName) && isset($data)) {
                  try { $this->check($fieldName, $this->data->selected); }
                  catch (\Kabas\Exceptions\TypeException $e) {
                        echo $e->getMessage();
                  }
            }
      }

      /**
       * Condition to check if the value is correct for this field type.
       * @param  mixed $value
       * @return bool
       */
      public function condition()
      {
            return is_bool($this->data);
      }

      /**
       * Get option's label
       * @return string
       */
      public function label()
      {
            return $this->data->label;
      }

      /**
       * Get option's selected state
       * @return bool
       */
      public function isSelected()
      {
            return $this->data->selected;
      }

}
