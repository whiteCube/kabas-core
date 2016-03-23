<?php

namespace Kabas\Config\FieldTypes;

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

      public function condition($value)
      {
            return is_bool($value);
      }

      public function __toString()
      {
            $this->isSelected() ? $string = "true" : $string = "false";
            return $string;
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
