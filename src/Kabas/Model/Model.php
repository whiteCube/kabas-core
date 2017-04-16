<?php

namespace Kabas\Model;

use Kabas\App;
use Kabas\Utils\Text;

class Model
{
      protected $driver;
      protected $fields;
      protected $model;
      protected static $table;
      protected static $fillable;
      protected static $guarded;
      protected static $instance;

      public function __construct()
      {
            self::$instance = $this;
            $this->makeModel();
      }

      public function __set($name, $value)
      {
            $instance = self::getInstance();
            $instance->model->$name = $value;
      }

      public function __call($name, $args)
      {
            $instance = self::getInstance();
            $resp = call_user_func_array([$instance->model, $name], $args);
            return $resp;
      }

      public static function __callStatic($name, $args)
      {
            $instance = new static;
            $resp = call_user_func_array([$instance->model, $name], $args);
            return $resp;
      }

      /**
       * Get instance of this Model class.
       * @return $this
       */
      static function getInstance()
      {
            if(!isset(self::$instance)) self::$instance = new static;
            return self::$instance;
      }

      /**
       * Get the model directory and filename
       * @return string
       */
      public function getModelStructure($name)
      {
            $path = THEME_PATH . DS . 'structures' . DS . 'models' . DS;
            $path .= strtolower($name) . '.json';
            return realpath($path);
      }

      /**
       * Create an instance of the proper driver.
       * @return void
       */
      private function makeModel()
      {
            $this->checkDriver();
            $class = '\\Kabas\\Drivers\\' . Text::toNamespace($this->driver);
            $info = new \stdClass();
            $info->name = Text::removeNamespace(get_class($this));
            $info->table = static::$table;
            $info->fillable = static::$fillable;
            $info->guarded = static::$guarded;
            $info->structure = $this->getModelStructure($info->name);
            $this->model = new $class([], $info);
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
