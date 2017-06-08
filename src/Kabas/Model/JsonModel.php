<?php

namespace Kabas\Model;

use Kabas\Drivers\Json;

class JsonModel extends Model implements ModelInterface
{
    public function makeDriverInstance()
    {
        $info = new \stdClass();
        return new Json($info);
    }
}
