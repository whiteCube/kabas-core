<?php

namespace Kabas\Drivers;

use \Kabas\App;
use \Kabas\Utils\File;

class Json
{
      protected static $instance;
      protected static $modelInfo;
      protected $attributes = [];

      public function __construct($uselesss = null, $modelInfo = null)
      {
            self::$instance = $this;
            if(isset($modelInfo)) {
                  self::$modelInfo = $modelInfo;
                  App::config()->models->loadModel($modelInfo);
            }
      }

      public static function __callStatic($method, $parameters)
      {
            $instance = self::getInstance();
            if(method_exists($instance, $method)) call_user_func_array([$instance, $method], $parameters);
      }

      public function __set($name, $value)
      {
            $this->attributes[$name] = $value;
      }

      public function __get($name)
      {
            return $this->attributes[$name];
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
       * Get the current stack of items if it exists,
       * if not create one by getting all items.
       * @return array
       */
      protected function getStackedItems()
      {
            $instance = self::getInstance();
            if(!isset($this->stackedItems)) return File::loadJsonFromDir($instance->getContentPath());
            return $this->stackedItems;
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

      /**
       * Specify a condition for the items to retrieve.
       * @param  string $column
       * @param  string $operator
       * @param  mixed $value
       * @param  bool $boolean
       * @return $this
       */
      public function where($column, $arg1 = null, $arg2 = null, $boolean = null)
      {
            switch(func_num_args()) {
                  case 2: $this->applyWhere($column, '=', $arg1); break;
                  default: $this->applyWhere($column, $arg1, $arg2);
            }
            return $this;
      }

      protected function applyWhere($column, $operator, $value)
      {
            $items = $this->getStackedItems();
            $this->stackedItems = [];
            foreach($items as $item) {
                  if(isset($item->$column) && $this->testCondition($operator, $item->$column, $value)) {
                        $this->stackedItems[] = $item;
                  }
            }
      }

      protected function testCondition($operator, $v, $value)
      {
            switch($operator){
                  case '=':   return $v === $value; break;
                  case '!=':  return $v !== $value; break;
                  case '>':   return $v > $value; break;
                  case '>=':  return $v >= $value; break;
                  case '<':   return $v < $value; break;
                  case '<=':  return $v <= $value; break;
                  default:    return $v === $value;
            }
      }

      /**
       * Limit the number of entries you recieve.
       * @param  int $limit
       * @return $this
       */
      public function limit($limit)
      {
            $limit = intval($limit);
            if($limit <= 0) return 'error';
            $this->limit = $limit;
            return $this;
      }

      /**
       * Sort the items.
       * @param  string $column
       * @param  string $direction
       * @return $this
       */
      public function orderBy($column, $direction = 'asc')
      {
            $items = $this->getStackedItems();

            usort($items, function($a, $b) use ($column, $direction) {
                  if($direction === 'asc') return $a->$column > $b->$column;
                  if($direction === 'desc') return $a->$column < $b->$column;
            });

            $this->stackedItems = $items;

            return $this;

      }

      /**
       * Get the fields by instanciating each stacked item.
       * @param  array $columns
       * @return array
       */
      public function get($columns = null)
      {
            $stackedItems = $this->applyLimit();
            $items = [];
            foreach($stackedItems as $item) {
                  $items[] = $this->instanciateFields($item, $columns);
            }
            return $items;
      }

      /**
       * Applies the defined item count limit.
       * @return array
       */
      public function applyLimit()
      {
            if(isset($this->limit)) return array_slice($this->getStackedItems(), 0, $this->limit);
            return $this->getStackedItems();
      }

      /**
       * Save the current model.
       * @return void
       */
      public function save()
      {
            $this->create($this->attributes);
      }

      /**
       * Create a new json file with the provided data.
       * @param  array $data
       * @return void
       */
      public function create($data)
      {
            if(!isset($data['id'])) $data['id'] = $this->getAutoIncrementedId();
            File::writeJson($data, $this->getContentPath() . DS . $data['id'] . '.json');
      }

      /**
       * Takes the last item in the folder and increments its ID.
       * @return int
       */
      protected function getAutoIncrementedId()
      {
            $files = scandir($this->getContentPath());
            $lastIndex = count($files) - 1;
            $lastId = intval(pathinfo($files[$lastIndex], PATHINFO_FILENAME));
            return ++$lastId;
      }

}
