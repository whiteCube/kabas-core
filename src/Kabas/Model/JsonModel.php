<?php

namespace Kabas\Model;

use Kabas\Drivers\Json;

class JsonModel extends Model implements ModelInterface
{
    public function getDriverInstance()
    {
        return new Json($this);
    }
}
