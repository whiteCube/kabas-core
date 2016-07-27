<?php

namespace Kabas\Fields;

use \Kabas\App;
use \Kabas\Exceptions\TypeException;

class Selectable implements \IteratorAggregate
{
      public function __construct($fieldName = null, $data = null)
      {
            $this->fieldName = $fieldName;
            $data = (array) $data;
            foreach($data as $option) {
                  $this->data[$option->id] = App::getInstance()->make('\Kabas\Fields\Option', [$option]);
            }
            if(!$this->allowsMultipleValues && isset($fieldName)) {
                  try { $this->checkValues(); }
                  catch (TypeException $e) {
                        echo $e->getMessage();
                  }
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

      public function getIterator()
      {
            return new \ArrayIterator($this->data);
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
       * @return Kabas\Fields\Option
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

            foreach($this->data as $option) {
                  if($option->isSelected()) $array[] = $option;
            }

            return $array;
      }

      private function checkValues()
      {
            $selectedCount = 0;
            foreach($this->data as $option) {
                  if($option->isSelected()) $selectedCount++;
            }
            if($selectedCount > 1) {
                  throw new TypeException('Field "' . $this->fieldName . '" only allows one selected value');
            }
      }
}
