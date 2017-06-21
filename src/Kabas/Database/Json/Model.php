<?php

namespace Kabas\Database\Json;

use Kabas\Database\ModelInterface;
use Kabas\Database\Model as BaseModel;
use Kabas\Database\Json\Builder;
use Kabas\Database\Json\Query;

abstract class Model extends BaseModel implements ModelInterface
{
    /**
     * Create a new JSON query builder for the model.
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Kabas\Database\Json\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Get the repository qualified key name (=alias for the filename).
     * @return string
     */
    public function getQualifiedKeyName()
    {
        return $this->getKeyName();
    }

    /**
     * Get a new JSON query builder instance for the connection.
     * @return \Kabas\Database\Json\Query
     */
    protected function newBaseQueryBuilder()
    {
        // TODO : new processor should maybe be a static::$processor
        return new Query(new static, new Processor);
    }
}
