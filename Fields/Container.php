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
       * Get a FieldType class if it exists
       * @param  string $type
       * @return string
       */
      public function getClass($type)
      {
            if(isset($this->supportedTypes[$type])) return $this->supportedTypes[$type]->class;
            else{
                  $error = 'Type "' . $type . '" is not a supported field type.';
                  throw new \Kabas\Exceptions\TypeException($error);
            }
      }

}
