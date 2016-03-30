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
       * Load the names of the supported field types.
       * @return void
       */
      public function loadFieldTypes()
      {
            $path = __DIR__ . DIRECTORY_SEPARATOR . 'Types' . DIRECTORY_SEPARATOR;
            $data = scandir($path);
            foreach($data as $file) {
                  if($file !== '.' && $file !== '..') {
                        $this->supportedTypes[] = strtolower(basename($file, '.php'));
                  }
            }
      }

      /**
       * Load a field type
       * @param  string $fieldType
       * @return void
       */
      public function loadFieldType($fieldType)
      {
            $filename = ucfirst($fieldType) . '.php';
            $path = __DIR__ . DIRECTORY_SEPARATOR . 'Types' . DIRECTORY_SEPARATOR;
            require_once($path . $filename);
            $class = '\Kabas\Config\FieldTypes\\' . ucfirst($fieldType);
            if(class_exists($class)) {
                  $instance = App::getInstance()->make($class);
                  $this->types[$instance->type] = $instance;
            }
      }

      /**
       * Check if field type is supported
       * @param  string $type
       * @return boolean
       */
      public function exists($type)
      {
            if(in_array($type, $this->supportedTypes)) {
                  if(!array_key_exists($type, $this->types)) $this->loadFieldType($type);
                  return true;
            } else {
                  $error = 'Type "' . $type . '" is not a supported field type.';
                  throw new \Kabas\Exceptions\TypeException($error);
            }
      }

}
