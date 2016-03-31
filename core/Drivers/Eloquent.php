<?php

namespace Kabas\Drivers;

use Kabas\App;
use \Illuminate\Database\Eloquent\Model as IlluminateEloquent;
use \Illuminate\Database\Capsule\Manager as Capsule;

class Eloquent extends IlluminateEloquent
{
      protected static $kabasTable;

      public function __construct(array $attributes = [], $table = null)
      {
            self::$kabasTable = $table;
            $this->table = $table;
            $this->capsule = new Capsule;
            $this->capsule->addConnection(App::config()->appConfig['mysql']);
            $this->capsule->bootEloquent();
            parent::__construct($attributes);
      }

      /**
       *
       *	All of the code below is overridden Eloquent methods we had to tweak
       *	in order to keep a reference to the static::$kabasTable property
       *	everytime this class is instanciated. Best not to touch!
       * 	
       */

      public static function observe($class, $priority = 0)
      {
            $instance = new static([], static::$kabasTable);
            $className = is_string($class) ? $class : get_class($class);
            foreach ($instance->getObservableEvents() as $event) {
                  if (method_exists($class, $event)) {
                        static::registerModelEvent($event, $className.'@'.$event, $priority);
                  }
            }
      }

      public function newInstance($attributes = [], $exists = false)
      {
            $model = new static((array) $attributes, static::$kabasTable);
            $model->exists = $exists;
            return $model;
      }


      public static function hydrate(array $items, $connection = null)
      {
            $instance = (new static([], static::$kabasTable))->setConnection($connection);
            $items = array_map(function ($item) use ($instance) {
                  return $instance->newFromBuilder($item);
            }, $items);
            return $instance->newCollection($items);
      }

      public static function hydrateRaw($query, $bindings = [], $connection = null)
      {
            $instance = (new static([], static::$kabasTable))->setConnection($connection);
            $items = $instance->getConnection()->select($query, $bindings);
            return static::hydrate($items, $connection);
      }

      public static function create(array $attributes = [])
      {
            $model = new static($attributes, static::$kabasTable);
            $model->save();
            return $model;
      }

      public static function forceCreate(array $attributes)
      {
            $model = new static([], static::$kabasTable);
            return static::unguarded(function () use ($model, $attributes) {
                  return $model->create($attributes);
            });
      }

      public static function query()
      {
            return (new static([], static::$kabasTable))->newQuery();
      }

      public static function on($connection = null)
      {
            $instance = new static([], static::$kabasTable);
            $instance->setConnection($connection);
            return $instance->newQuery();
      }

      public static function onWriteConnection()
      {
            $instance = new static([], static::$kabasTable);
            return $instance->newQuery()->useWritePdo();
      }

      public static function all($columns = ['*'])
      {
            $columns = is_array($columns) ? $columns : func_get_args();
            $instance = new static([], static::$kabasTable);
            return $instance->newQuery()->get($columns);
      }

      public static function with($relations)
      {
            if (is_string($relations)) {
                  $relations = func_get_args();
            }
            $instance = new static([], static::$kabasTable);
            return $instance->newQuery()->with($relations);
      }

      public static function destroy($ids)
      {
            $count = 0;
            $ids = is_array($ids) ? $ids : func_get_args();
            $instance = new static([], static::$kabasTable);
            $key = $instance->getKeyName();
            foreach ($instance->whereIn($key, $ids)->get() as $model) {
                  if ($model->delete()) {
                        $count++;
                  }
            }
            return $count;
      }

      public static function flushEventListeners()
      {
            if (! isset(static::$dispatcher)) {
            return;
            }
            $instance = new static([], static::$kabasTable);
            foreach ($instance->getObservableEvents() as $event) {
            static::$dispatcher->forget("eloquent.{$event}: ".static::class);
            }
      }

      public function replicate(array $except = null)
      {
            $except = $except ?: [
                  $this->getKeyName(),
                  $this->getCreatedAtColumn(),
                  $this->getUpdatedAtColumn(),
            ];
            $attributes = Arr::except($this->attributes, $except);
            $instance = new static([], static::$kabasTable);
            $instance->setRawAttributes($attributes);
            return $instance->setRelations($this->relations);
      }

      public static function __callStatic($method, $parameters)
      {
            $instance = new static([], static::$kabasTable);
            return call_user_func_array([$instance, $method], $parameters);
      }

}
