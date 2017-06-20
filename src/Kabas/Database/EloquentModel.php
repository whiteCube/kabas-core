<?php

namespace Kabas\Database;

use Kabas\Drivers\Eloquent;

class EloquentModel extends Model implements ModelInterface
{
    protected $connection = 'eloquent';

    /**
     * assign table name from static repository attribute
     * @param  array  $attributes
     */
    public function __construct($attributes = [])
    {
        $this->table = static::$repository;
        parent::__construct($attributes);
    }

    /**
     * Fill the model with an array of attributes.
     * @param  array  $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        parent::fill($attributes)->makeFieldsFromRawAttribbutes($attributes);
        return $this;
    }
    
    /**
     * Create a new model instance that is existing.
     * @param  array  $attributes
     * @param  string|null  $connection
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = parent::newFromBuilder($attributes, $connection);
        $model->updateFieldsFromRawAttributes((array) $attributes);
        return $model;
    }
}
