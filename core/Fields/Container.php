<?php

namespace Kabas\Fields;

use \Kabas\App;
use \Kabas\Utils\Text;

class Container
{
      /**
       * All the supported field types
       * @var array
       */
      public $supportedTypes = [];

      /**
       * Instances of the field types we need
       * @var array
       */
      public $types = [];

      public function __construct()
      {
            foreach(scandir( __DIR__ . DS . 'Types' ) as $file) {
                  if($file !== '.' && $file !== '..') {
                        $this->loadFieldType($file);
                  }
            }
      }

      /**
       * Load a field type
       * @param  string $fieldType
       * @return void
       */
      public function loadFieldType($file)
      {
            $type = $this->getFieldTypeObject($file);
            $this->supportedTypes[$type->name] = $type;
      }

      /**
       * Get FieldType info
       * @param  string $file
       * @return object
       */
      protected function getFieldTypeObject($file)
      {
            $type = new \stdClass();
            $type->name = strtolower(basename($file, '.php'));
            $type->class = '\\Kabas\\Fields\\Types\\' . Text::toNamespace($type->name);
            return $type;
      }

      /**
       * Check if field type is supported
       * @param  string $type
       * @return boolean
       */
      public function exists($type)
      {
            if($this->getClass($type)) return true;
            return false;
      }

      /**
       * Get a FieldType class if it exists
       * @param  string $type
       * @return string | bool
       */
      public function getClass($type)
      {
            if(isset($this->supportedTypes[$type])) return $this->supportedTypes[$type]->class;
            return false;
      }

}
