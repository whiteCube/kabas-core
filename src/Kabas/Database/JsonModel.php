<?php

namespace Kabas\Database;

abstract class JsonModel extends Model implements ModelInterface
{
    protected $connection = 'filesystem';
}
