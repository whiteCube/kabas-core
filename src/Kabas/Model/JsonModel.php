<?php

namespace Kabas\Model;

use Kabas\Drivers\Json;
use \Kabas\Utils\Lang;

class JsonModel extends Model implements ModelInterface
{
    public function getDriverInstance()
    {
        return new Json($this, Lang::getOrDefault());
    }
}
