<?php

namespace Tests\Database;

use Kabas\Database\Eloquent\Model;

class EloquentModel extends Model
{
    protected $fillable = ['foo'];
    protected $table = 'test';
}
