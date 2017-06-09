<?php

namespace Kabas\Drivers;

use Kabas\Model\ModelInterface;
use Kabas\Config\Language;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Exceptions\ModelNotFoundException;
use \Kabas\Exceptions\MassAssignmentException;

class Json
{
    /**
    * Reference to the model that needs to be handled
    * @var object
    */
    protected $model;

    /**
    * Default locale to look for
    * @var string
    */
    protected $locale;

    /**
    * Query results container
    * @var array
    */
    protected $stacked = [];

    public function __construct(ModelInterface $model, Language $language)
    {
        $this->model = $model;
        $this->locale = $language->original;
    }

    /**
     * Get the path to a given locale's content folder in filesystem
     * @param string $locale
     * @return string
     */
    public function getContentPath($locale = null)
    {
        if(!$locale) $locale = $this->locale;
        return CONTENT_PATH . DS . $locale . DS . $this->model->getRepository();
    }

    /**
     * Adds model instances for the given raw objects to the current stack
     * @param array $items
     * @return void
     */
    protected function stackItems(array $items)
    {
        $model = get_class($this->model);
        foreach($items as $item) {
            $this->stacked[] = new $model($this->getAttributesArrayFromRawData($item->data ?? null));
        }
    }

    /**
     * Returns a clean array containing key/values from given raw object
     * @param object $data
     * @return void
     */
    protected function getAttributesArrayFromRawData($data)
    {
        $model = [];
        if(!$data) return $model;
        foreach ($this->model->getFields() as $key => $value) {
            if(!isset($data->$key)) continue;
            $model[$key] = $data->$key;
        }
        return $model;
    }

    /**
     * Get all entries for current model
     * Columns argument is ignored
     * @return array|null
     */
    public function all($columns = null)
    {
        $this->stackItems(File::loadJsonFromDir($this->getContentPath()));
        if(!count($this->stacked)) return null;
        return $this->stacked;
    }

    /**
     * Find entries by id
     * @param  mixed $key
     * @param  array $columns
     * @return object
     */
    public function find($key, $columns = null)
    {
        if($columns) $this->columns = $columns;
        if(is_array($key)) {
            $this->findMany($key, $this->columns);
        } else {
            $path = $this->getContentPath() . DS . $key . '.json';
            $item = File::loadJson($path);
            if(empty($item)) return null;
            $item = $this->getColumns($item, $this->columns);
            $this->attributes = (array) $this->instanciateFields($item, $this->columns);
        }
        return $this;
    }

    /**
     * Find multiple entries.
     * @param  array $ids
     * @param  array $columns
     * @return $this
     */
    public function findMany($ids, $columns = null)
    {
        if($columns) $this->columns = $columns;
        foreach($ids as $id) {
            $path = $this->getContentPath() . DS . $id . '.json';
            $item = File::loadJson($path);
            if($item){
                $this->stacked[$id] = $this->instanciateFields($item, $this->columns);
            }
        }
        return $this;
    }

    /**
     * Specify a condition for the items to retrieve.
     * @param  string $column
     * @param  string $arg1
     * @param  mixed $arg2
     * @param  bool $boolean
     * @return $this
     */
    public function where($column, $arg1 = null, $arg2 = null, $boolean = null)
    {

        if(is_array($column)) {
            foreach($column as $key => $value){
                $this->where($key, $value);
            }
            return $this;
        }
        $items = $this->getStackedItems();
        switch(func_num_args()) {
            case 2: $result = $this->applyWhere($column, '=', $arg1, $items); break;
            default: $result = $this->applyWhere($column, $arg1, $arg2, $items);
        }
        $this->stacked = $result;
        return $this;
    }

    /**
     * Add an "or where" clause to the query.
     * @param  string $column
     * @param  string $arg1
     * @param  mixed $arg2
     * @return $this
     */
    public function orWhere($column, $arg1 = null, $arg2 = null)
    {
        if(is_array($column)) {
            foreach($column as $key => $value){
                $this->where($key, $value);
            }
            return $this;
        }
        $items = File::loadJsonFromDir($this->getContentPath());
        switch(func_num_args()) {
            case 2: $result = $this->applyWhere($column, '=', $arg1, $items); break;
            default: $result = $this->applyWhere($column, $arg1, $arg2, $items);
        }
        $this->stacked = array_merge($this->stacked, $result);
        return $this;
    }

    protected function applyWhere($column, $operator, $value, $items)
    {
        $result = [];
        foreach($items as $item) {
            if(isset($item->$column) && $this->testCondition($operator, $item->$column, $value)) {
                $result[] = $item;
            }
        }
        return $result;
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

        $this->stacked = $items;

        return $this;

    }

    /**
     * Get the fields by instanciating each stacked item.
     * @param  array $columns
     * @return array
     */
    public function get($columns = null)
    {
        if($columns) $this->columns = $columns;
        $stackedItems = $this->applyLimit();
        $this->stacked = [];
        if(count($stackedItems) === 0) return null;
        if(count($stackedItems) === 1) {
            $this->attributes = (array) $this->instanciateFields($stackedItems[0], $this->columns);
            return $this;
        }
        foreach($stackedItems as $item) {
            $this->stacked[] = $this->instanciateFields($item, $this->columns);
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
        return isset($this->stacked[0]->id) || isset($this->attributes['id']);
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
        if(!empty($this->stacked)) {
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
            if($this->isFillable($key)) $this->$key = $value;
            else throw new MassAssignmentException($key);
        }
        return $this;
    }

    /**
     * Get the fillable attributes for the model.
     * @return array
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Get the guarded attributes for the model.
     * @return array
     */
    public function getGuarded()
    {
        return $this->guarded;
    }

    /**
     * Determine if the given attribute may be mass assigned.
     * @param  string  $key
     * @return bool
     */
    public function isFillable($key)
    {
        if(in_array($key, $this->getFillable())) return true;
        if($this->isGuarded($key)) return false;
    }

    /**
     * Determine if the given key is guarded.
     *
     * @param  string  $key
     * @return bool
     */
    public function isGuarded($key)
    {
        return in_array($key, $this->getGuarded()) || $this->getGuarded() == ['*'];
    }

    /**
     * Get the first item from the stack.
     * @return $this|null
     */
    public function first($columns = null)
    {
        $items = $this->getStackedItems();
        if($items){
            $this->stacked = [];
            $this->attributes = (array) $this->instanciateFields($items[0], $columns);
            return $this;
        }
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

    /**
     * Get a single column's value from the first result of the query.
     * @param  string $column
     * @return mixed
     */
    public function value($column)
    {
        $item = $this->getStackedItems()[0];
        return $item->$column;
    }

    /**
     * Find a model or throw an exception
     * @param  string $id
     * @param  array $columns
     * @return $this
     */
    public function findOrFail($id, $columns = null)
    {
        $result = $this->find($id, $columns);
        if(!$result) throw new ModelNotFoundException(self::$modelInfo->table);
        return $this;
    }

    /**
     * Fetch the first model or throw an exception.
     * @param  array $columns
     * @return $this
     */
    public function firstOrFail($columns = null)
    {
        $item = $this->first($columns);
        if(!$item) throw new ModelNotFoundException(self::$modelInfo->table);
        return $this;
    }

    /**
     * Find the models or return a fresh instance
     * @param  string $id
     * @param  array $columns
     * @return $this
     */
    public function findOrNew($id, $columns = null)
    {
        $this->find($id, $columns);
        return $this;
    }

    /**
     * Get the first record matching the attributes or instanciate it.
     * @param  array $attributes
     * @return $this
     */
    public function firstOrNew($attributes)
    {
        $this->where($attributes)->first();
        return $this;
    }

    /**
     * Get the first record matching the attributes or create it.
     * @param  array $attributes
     * @return $this
     */
    public function firstOrCreate($attributes)
    {
        $item = $this->where($attributes)->first();
        if(!$item) $this->create($attributes);
        return $this;
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     * @param  array $attributes
     * @param  array $values
     * @return $this
     */
    public function updateOrCreate($attributes, $values = [])
    {
        $item = $this->firstOrNew($attributes);
        $item->fill($values)->save();
        return $this;
    }

    /**
     * Alias for the "pluck" method
     * @param  string $column
     * @return $this
     */
    public function lists($column)
    {
        return $this->pluck($column);
    }

    /**
     * Get an array with the values of a given column.
     * @param  string $column
     * @return array
     */
    public function pluck($column)
    {
        $items = $this->getStackedItems();
        foreach($items as $key => $item){
            $item = $this->instanciateFields($item, [$column]);
            if(property_exists($item, $column)){
                $items[$key] = $item->$column;
            } else {
                $items[$key] = null;
            }
        }
        return $items;
    }

    /**
     * Increment a column's value by a given amount.
     * @param  string  $column
     * @param  integer $amount
     * @return void
     */
    public function increment($column, $amount = 1)
    {
        $items = $this->getStackedItems();
        foreach($items as $item) {
            $item->$column += $amount;
        }
        $this->save();
    }

    /**
    * Decrement a column's value by a given amount.
    * @param  string  $column
    * @param  integer $amount
    * @return void
    */
    public function decrement($column, $amount = 1)
    {
        $items = $this->getStackedItems();
        foreach($items as $item) {
            $item->$column -= $amount;
        }
        $this->save();
    }

    /**
     * Set the columns to be selected.
     * @param  string|array $columns
     * @return $this
     */
    public function select($columns)
    {
        if(is_string($columns)) $columns = [$columns];
        $this->columns = $columns;
        return $this;
    }

    /**
     * Add columns to be selected.
     * @param string|array $columns
     * @return $this
     */
    public function addSelect($columns)
    {
        if(is_string($columns)) $columns = [$columns];
        $this->columns = array_merge($this->columns, $columns);
        return $this;
    }

}
