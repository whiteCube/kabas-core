<?php

namespace Kabas\Fields;

class Item
{
      public $type;

      public $name;

      protected $output;

      protected $value;

      protected $default;

      protected $label;

      protected $description;

      protected $options;

      protected $multiple = false;


      public function __construct($name = null, $value = null, $structure = null)
      {
            $this->name = $name;
            $this->implement($structure);
            $this->set($value);
      }

      public function __toString()
      {
            return (string) $this->output;
      }

      public function __call($name, $arguments)
      {
            if(!method_exists($this, $name)) {
                  $error = 'Error: Method "' . $name . '" does not exist for field type "' . $this->type .'".';
                  throw new \Exception($error);
            }
      }

      /**
       * Defines field's value and updates its output
       * @param  mixed $value
       * @return void
       */
      public function set($value)
      {
            if($this->default && is_null($value)) $value = $this->default;
            if($this->multiple && !is_array($value) && !is_null($value)) $value = [$value];
            $this->value = $value;
            $this->output = $this->parse($value);

            if(!is_null($this->name) && !is_null($this->value)) {
                  try { $this->check(); }
                  catch (\Kabas\Exceptions\TypeException $e) {
                        echo $e->getMessage();
                  }
            }
      }

      /**
       * Defines multiple-values mode
       * @param  boolean $value
       * @return void
       */
      public function setMultiple($value = null)
      {
            $this->multiple = is_null($value) ? $this->multiple : $value;
      }

      /**
       * Generates the right form tag for this field
       * @param  array $attributes
       * @return string
       */
      public function input($attributes = [])
      {
            // TODO: make this rock.
            // $attributes
            // return '<input type="' . $this->type . '" value="' . $val . '" />';
      }

      /**
       * Runs a field validation on the given/current value
       * and throws an error if it returns false.
       * @param  string $field
       * @param  mixed $value
       * @return void
       */
      public function check($value = null)
      {
            if(!$this->validate($value)) {
                  $error = 'Field "' . $this->name . '" of type "' . $this->type . '" has an incorrect value.';
                  throw new \Kabas\Exceptions\TypeException($error);
            }
      }

      /**
       * Validates the given/current value
       * @param  string $field
       * @param  mixed $value
       * @return void
       */
      public function validate($value = null)
      {
            $value = is_null($value) ? $this->value : $value;
            return $this->condition($value);
      }

      /**
       * Builds field's attributes based on user-defined structure
       * @param  object $structure
       * @return void
       */
      protected function implement($structure)
      {
            $this->default = @$structure->default;
            $this->label = isset($structure->label) ? trim($structure->label) : ucfirst($this->type);
            $this->description = isset($structure->description) ? $structure->description : null;
      }

      /**
       * Makes an output value from given raw value
       * @param  mixed $value
       * @return mixed
       */
      protected function parse($value)
      {
            return $value;
      }
}
