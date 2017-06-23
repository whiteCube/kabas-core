<?php

namespace Tests\Database;

use Kabas\Database\Eloquent\Model as KabasEloquentModel;

class EloquentModel extends KabasEloquentModel
{
    protected $fillable = ['foo'];
}
