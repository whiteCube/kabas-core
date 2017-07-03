<?php 

namespace Kabas\Utils;

use Kabas\App;

class Uploads {

    static function __callStatic($method, $params)
    {
        return call_user_func_array([App::uploads(), $method], $params);
    }

}