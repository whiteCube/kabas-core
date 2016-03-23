<?php

namespace Kabas\Config\FieldTypes;

class Selectable
{
      public function __construct($fieldName = null, $data = null)
      {
            $this->fieldName = $fieldName;
            $data = (array) $data;
            foreach($data as $option) {
                  $this->data[$option->id] = new Option($option);
            }
      }

      public function __call($name, $arguments)
      {
            reset($this->data);
            $key = key($this->data);
            if(!method_exists($this, $name) && method_exists($this->data[$key], $name)) {
                  return $this->data[$key]->$name($arguments);
            }
      }

      /**
       * Get all options
       * @return array
       */
      public function all()
      {
            return $this->data;
      }

      /**
       * Get an option by ID
       * @param  string $id
       * @return Kabas\Config\FieldTypes\Option
       */
      public function get($id)
      {
            return $this->data[$id];
      }

      /**
       * Get all selected options
       * @return array
       */
      public function getSelected()
      {
            $array = [];

            foreach($this->data as $checkbox) {
                  if($checkbox->isSelected()) $array[] = $checkbox;
            }

            return $array;
      }
}
