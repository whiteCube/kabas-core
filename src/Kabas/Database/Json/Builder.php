<?php

namespace Kabas\Database\Json;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * This class could be obsolete
 * and should be deleted if it remains empty.
 */
class Builder extends EloquentBuilder
{
    /**
     * Create a new instance of the model being queried.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newModelInstance($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }
 
}