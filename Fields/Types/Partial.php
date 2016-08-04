<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Groupable;

class Partial extends Groupable
{
      public $type = "partial";

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
       * retrieves current option key
       * @return string
       */
      public function option()
      {
            return $this->option;
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
            $this->setOption(@$structure->option);
      }

      /**
       * Sets option key if exists (used inside flexibleContent)
       * @param  string $option
       * @return void
       */
      public function setOption($option = null)
      {
            $this->option = is_string($option) ? $option : false;
      }

      /**
       * makes options from user defined list
       * @param  string $options
       * @return array
       */
      protected function makeOptions($options)
      {
            $a = [];
            $fields = $this->getPartFields($options);
            foreach($options as $name => $field){
                  $item = new \stdClass();
                  $item->name = $name;
                  $item->class = App::fields()->getClass(isset($field->type) ? $field->type : 'text');
                  $item->structure = $field;
                  $a[] = $item;
            }
            return $a;
      }

      /**
       * Gets requested partial's fields
       * @param  string $part
       * @return array
       */
      protected function getPartFields($part)
      {
            $part = App::content()->partials->load($part);
            // TO DO :
            //    - check if part exists
            //    - store part in this field (so it can be rendered)
            //    - return part's fields structure
            var_dump($part);die();
      }


      /**
       * Makes an array of defined fields
       * @param  object $value
       * @return array
       */
      protected function parse($value)
      {
            $a = [];
            foreach ($this->options as $field) {
                  if($field->class){
                        $key = $field->name;
                        $val = isset($value->$key) ? $value->$key : null;
                        $a[$key] = App::getInstance()->make($field->class, [$key, $val, $field->structure]);
                  }
            }
            return $a;
      }
}
