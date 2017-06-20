<?php

namespace Tests\Model;

use Kabas\Database\EloquentModel as KabasEloquentModel;

class EloquentModel extends KabasEloquentModel
{
    protected $fillable = ['foo'];
}
