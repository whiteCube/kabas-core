<?php

namespace Kabas\Config\FieldTypes;

use Kabas\App;

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
            $this->loadFieldTypes();
      }

      /**
       * Load the supported field types.
       * @return void
       */
      public function loadFieldTypes()
      {
            $path = __DIR__ . DS . 'Types' . DS;
            $data = scandir($path);
            foreach($data as $file) {
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
            require_once($type->path);
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
            $type->class = '\Kabas\Config\FieldTypes\\' . ucfirst($type->name);
            $type->path = __DIR__ . DS . 'Types' . DS . $file;
            return $type;
      }

      /**
       * Check if field type is supported
       * @param  string $type
       * @return boolean
       */
      public function exists($type)
      {
            if($this->getClass($type)) {
                  return true;
            } else {
                  $error = 'Type "' . $type . '" is not a supported field type.';
                  throw new \Kabas\Exceptions\TypeException($error);
            }
      }

      /**
       * Get a FieldType class if it exists
       * @param  string $type
       * @return string | bool
       */
      public function getClass($type)
      {
            if(isset($this->supportedTypes[$type])) return $this->supportedTypes[$type];
            return false;
      }

}
