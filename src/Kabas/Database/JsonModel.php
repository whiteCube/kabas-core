<?php

namespace Kabas\Database;

abstract class JsonModel extends Model implements ModelInterface
{
    public function getDriverInstance()
    {
        return new Json($this, Lang::getOrDefault());
    }
}
