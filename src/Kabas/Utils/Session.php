<?php

namespace Kabas\Utils;

use Kabas\App;

class Session
{
    static function __callStatic($method, $params)
    {
        return call_user_func_array([App::session(), $method], $params);
    }
}
