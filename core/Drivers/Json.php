<?php

namespace Kabas\Drivers;

use \Kabas\App;
use \Kabas\Utils\File;

class Json implements \IteratorAggregate
{
      protected static $instance;
      protected static $modelInfo;
      protected $attributes = [];
      protected $hasStacked;
      protected $limit;
      protected $stackedItems = [];

      public function __construct($uselesss = null, $modelInfo = null)
      {
            self::$instance = $this;
            $this->hasStacked = false;
            $this->attributes['original'] = new \stdClass;
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
            $this->attributes['original']->$name = $value;
            $this->attributes[$name] = $value;
      }

      public function __get($name)
      {
            return $this->attributes[$name];
      }

      public function getIterator()
      {
            return new \ArrayIterator($this->stackedItems);
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
            if(is_null($item)) return;
            $item = $this->getColumns($item, $columns);
            $newItem = new \stdClass;
            $newItem->original = new \stdClass;
            foreach($item as $key => $value) {
                  $newItem->original->$key = $value;
                  if(!$this->isInstanciatedField($value)) {
                        $newItem->$key = $this->instanciateField($key, $value);
                  }
            }
            return $newItem;
      }

      /**
       * Check if field has already been instanciated.
       * @param  mixed  $field
       * @return boolean
       */
      public function isInstanciatedField($field)
      {
            if(is_object($field) && strpos(get_class($field), 'Kabas\Config\FieldTypes') !== false) return true;
            return false;
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
      public function getColumns($item, $columns)
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
            if(($this->hasStacked)) return $this->stackedItems;
            $this->hasStacked = true;
            return File::loadJsonFromDir($this->getContentPath());
      }

      /**
       * Get all entries
       * @return array
       */
      public function all($columns = null)
      {
            $path = $this->getContentPath();
            $items = File::loadJsonFromDir($path);
            foreach($items as $key => $item) {
                  $items[$key] = $this->instanciateFields($item, $columns);
            }
            $this->stackedItems = $items;
            if(count($this->stackedItems) === 0) return null;
            return $this;
      }

      /**
       * Find entries by id
       * @param  mixed $key
       * @param  array $columns
       * @return object
       */
      public function find($key, $columns = null)
      {
            if(is_array($key)) {
                  $this->findMany($key, $columns);
            } else {
                  $path = $this->getContentPath() . DS . $key . '.json';
                  $item = File::loadJson($path);
                  if(empty($item)) return null;
                  $item = $this->getColumns($item, $columns);
                  $this->attributes = (array) $this->instanciateFields($item, $columns);
            }
            return $this;
      }

      public function findMany($ids, $columns = null)
      {
            foreach($ids as $id) {
                  $path = $this->getContentPath() . DS . $id . '.json';
                  $item = File::loadJson($path);
                  if($item){
                        $this->stackedItems[$id] = $this->instanciateFields($item, $columns);
                  }
            }
            return $this;
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
       * Alias to limit.
       * @param  int $number
       * @return $this
       */
      public function take($number)
      {
            return $this->limit($number);
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
            $this->stackedItems = [];
            if(count($stackedItems) === 0) return null;
            if(count($stackedItems) === 1) {
                  $this->attributes = (array) $this->instanciateFields($stackedItems[0], $columns);
                  return $this;
            }
            foreach($stackedItems as $item) {
                  $this->stackedItems[] = $this->instanciateFields($item, $columns);
            }
            return $this;
      }

      /**
       * Applies the defined item count limit.
       * @return array
       */
      public function applyLimit()
      {
            if(!is_null($this->limit)) return array_slice($this->getStackedItems(), 0, $this->limit);
            return $this->getStackedItems();
      }

      /**
       * Check if current model exists.
       * @return bool
       */
      protected function exists($id = null)
      {
            if($id){
                  $results = $this->where('id', $id);
                  return count($results) > 0;
            }
            return isset($this->stackedItems[0]->id) || isset($this->attributes['id']);
      }

      /**
       * Save the current model.
       * @return $this
       */
      public function save()
      {
            $attributes = $this->attributes;

            if(!empty((array) $attributes['original'])){
                  if(!$this->exists()) $this->create($attributes['original']);
                  else {
                        $this->update($attributes['original']);
                        $this->attributes = (array) $this->instanciateFields($attributes, null);
                  }
            } else {
                  foreach($this->getStackedItems() as $item){
                        if(!$this->exists($item->id)) $this->create((array) $item);
                        else $this->update((array) $item);
                  }
            }

            return $this;
      }

      /**
       * Update a model.
       * @param  mixed $data
       * @return void
       */
      public function update($data)
      {
            $data = (array) $data;
            if(isset($data['updated_at'])) $data['updated_at'] = \Carbon\Carbon::now()->toDateTimeString();
            $this->create($data);
      }

      /**
       * Create a new json file with the provided data.
       * @param  array $data
       * @return void
       */
      public function create($data)
      {
            if(!isset($data['id'])) $data['id'] = $this->getAutoIncrementedId();
            File::writeJson($data, $this->getContentPath() . DS . $data['id']);
      }

      /**
       * Delete a single or multiple items.
       * @return void
       */
      public function delete()
      {
            $stackedItems = $this->applyLimit();
            if(empty($stackedItems)) {
                  File::deleteJson($this->getContentPath() . DS . $this->attributes['id']);
                  return;
            }
            foreach($stackedItems as $item) {
                  File::deleteJson($this->getContentPath() . DS . $item->id);
            }
      }

      /**
       * Deletes entries.
       * @param  mixed $ids
       * @return $this
       */
      public function destroy($ids)
      {
            if(!is_array($ids)) $ids = [$id];
            $this->find($ids);
            if(!empty($this->stackedItems)) {
                  $this->hasStacked = true;
                  $this->delete();
            }
            return $this;
      }

      /**
       * Fill the model with an array of attributes.
       * @param  array $data
       * @return $this
       */
      public function fill($data)
      {
            foreach($data as $key => $value){
                  $this->$key = $value;
            }
            return $this;
      }

      /**
       * Get the first item from the stack.
       * @return $this
       */
      public function first($columns = null)
      {
            $items = $this->getStackedItems();
            $this->stackedItems = [];
            $this->attributes = (array) $this->instanciateFields($items[0], $columns);
            return $this;
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

      /**
       * Update the model's update timestamp
       * @return void
       */
      public function touch()
      {
            return $this->save();
      }

      /**
       * Get the table for this model
       * @return string
       */
      public function getTable()
      {
            return self::$modelInfo->table;
      }

      /**
       * Convert the model to an array.
       * @return array
       */
      public function toArray()
      {
            $arr = [];
            $items = $this->getStackedItems();
            if(count($items) === 1) return (array) $this->instanciateFields($items[0], null);
            foreach($items as $item) {
                  $arr[] = (array) $this->instanciateFields($item, null);
            }
            return $arr;
      }

      /**
       * Convert the model instance to JSON.
       * @return string
       */
      public function toJson()
      {
            $items = $this->getStackedItems();
            if(count($items) === 1) $items = $items[0];
            return json_encode($items);
      }

}
