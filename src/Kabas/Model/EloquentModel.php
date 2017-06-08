<?php

namespace Kabas\Model;

use Kabas\Drivers\Eloquent;

class EloquentModel extends Model implements ModelInterface
{
    public function makeDriverInstance()
    {
        return new Eloquent([]);
    }
}
