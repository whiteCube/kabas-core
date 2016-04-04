<?php

namespace Kabas\Model;

use Kabas\App;
use Kabas\Utils\Text;

class Model
{
      protected $driver;
      protected $fields;
      protected static $table;
      protected static $instance;

      public function __construct()
      {
            $this->makeModel();
      }

      public static function __callStatic($name, $args)
      {
            $instance = new static;
            $resp = call_user_func_array([$instance->model, $name], $args);
            // var_dump($resp);
            return $resp;
      }

      /**
       * Get the model directory and filename
       * @return object
       */
      public function getDir()
      {
            $reflection = new \ReflectionClass($this);
            $dirname = pathinfo($reflection->getFileName())['dirname'];
            $modelData = new \stdClass();
            $modelData->path = $dirname;
            $files = scandir($dirname);
            foreach($files as $file) {
                  if(pathinfo($file)['extension'] === 'json') {
                        $modelData->filename = pathinfo($file)['filename'];
                        break;
                  }
            }
            return $modelData;
      }

      /**
       * Create an instance of the proper driver.
       * @return void
       */
      private function makeModel()
      {
            $this->checkDriver();
            $class = Text::toNamespace($this->driver);
            $modelInfo = new \stdClass();
            $modelInfo->table = static::$table;
            $modelInfo->path = $this->getDir();
            $this->model = App::getInstance()->make('Kabas\Drivers\\' . $class, [[], $modelInfo]);
      }

      /**
       * Check if custom driver has been defined for the model
       * and use the default one if not.
       * @return void
       */
      private function checkDriver()
      {
            if(!isset($this->driver)) $this->setDefaultDriver();
      }

      /**
       * Sets the driver to the default one.
       */
      private function setDefaultDriver()
      {
            $this->driver = App::config()->appConfig['driver'];
      }
}