<?php

namespace Kabas\Database;

use Kabas\Drivers\Eloquent;

class EloquentModel extends Model implements ModelInterface
{
    protected $connection = 'eloquent';

    public function __construct($attributes = [])
    {
        $this->table = static::$repository;
        parent::__construct($attributes);
    }
}
