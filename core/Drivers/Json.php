<?php

namespace Kabas\Drivers;

use \Kabas\App;
use \Kabas\Utils\File;

class Json
{
      protected static $instance;
      protected static $modelInfo;

      public function __construct($uselesss = null, $modelInfo = null)
      {
            self::$instance = $this;
            if(isset($modelInfo)) {
                  self::$modelInfo = $modelInfo;
                  App::config()->models->loadModel($modelInfo);
            }
      }

      /**
       * Get instance of this driver in static calls
       * @return \Kabas\Drivers\Json
       */
      static function getInstance()
      {
            if(!isset(self::$instance)) return new static;
            return self::$instance;
      }

      /**
       * Get the path to the proper content folder for the model.
       * @return string
       */
      public function getContentPath()
      {
            $lang = App::config()->settings->site->lang->active;
            return CONTENT_PATH . DS . $lang . DS . 'objects' . DS . self::$modelInfo->table;
      }

      /**
       * Call instanciateField on each field of the item.
       * @param  object $item
       * @param  array $columns
       * @return object
       */
      protected function instanciateFields($item, $columns)
      {
            $instance = self::getInstance();
            $item = self::getColumns($item, $columns);
            foreach($item as $key => $value) {
                  $item->$key = $instance->instanciateField($key, $value);
            }
            return $item;
      }

      /**
       * Get an instance of the proper FieldType
       * @param  string $key
       * @param  mixed $value
       * @return \Kabas\Config\FieldTypes\[type]
       */
      protected function instanciateField($key, $value)
      {
            $modelName = static::$modelInfo->path->filename;
            if(!App::config()->models->fieldExists($key, $modelName)) return $value;

            try {
                  $type = App::config()->models->items[$modelName]->$key->type;
                  App::config()->fieldTypes->exists($type);
            } catch (\Kabas\Exceptions\TypeException $e) {
                  $e->setFieldName($key, $modelName);
                  $e->showAvailableTypes();
                  echo $e->getMessage();
                  die();
            };

            $class = App::config()->fieldTypes->getClass($type)->class;
            return App::getInstance()->make($class, [$key, $value]);
      }

      /**
       * Only return the specified columns.
       * @param  object $item
       * @param  array $columns
       * @return object
       */
      static function getColumns($item, $columns)
      {
            if(!isset($columns)) return $item;
            $newItem = new \stdClass();
            foreach($columns as $column){
                  foreach($item as $key => $value) {
                        if($key === $column) $newItem->$key = $value;
                  }
            }
            return $newItem;
      }

      /**
       * Get all entries
       * @return array
       */
      static function all($columns = null)
      {
            $instance = self::getInstance();
            $path = $instance->getContentPath();
            $items = File::loadJsonFromDir($path);
            foreach($items as $key => $item) {
                  $items[$key] = $instance->instanciateFields($item, $columns);
            }
            return $items;
      }

      /**
       * Find a single entry by id
       * @param  string $id
       * @param  array $columns
       * @return object
       */
      static function find($id, $columns = null)
      {
            $instance = self::getInstance();
            $path = $instance->getContentPath() . DS . $id . '.json';
            $item = File::loadJson($path);
            $item = $instance->getColumns($item, $columns);
            $item = $instance->instanciateFields($item, $columns);
            return $item;
      }

}
