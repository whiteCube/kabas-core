<?php

namespace Kabas\Config\FieldTypes;

use Kabas\App;

class Container
{
      /**
       * All the supported field types
       * @var array
       */
      public $types;

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
            $path = __DIR__ . DIRECTORY_SEPARATOR . 'types' . DIRECTORY_SEPARATOR . '*.php';
            foreach(glob($path) as $file) {
                  require_once($file);
                  $class = '\Kabas\Config\FieldTypes\\' . basename($file, '.php');
                  if(class_exists($class)) {
                        $instance = new $class;
                        $this->types[$instance->type] = $instance;
                  }
            }
      }

      /**
       * Check if field type is supported
       * @param  string $type
       * @return boolean
       */
      public function exists($type)
      {
            if(array_key_exists($type, $this->types)) {
                  return true;
            } else {
                  $error = 'Type "' . $type . '" is not a supported field type.';
                  throw new \Kabas\Exceptions\TypeException($error);
            }
      }

}
