<?php

namespace Kabas\Database;

use Kabas\Drivers\Eloquent;

class EloquentModel extends Model implements ModelInterface
{
    public function getDriverInstance()
    {
        return new Eloquent([]);
    }
}
