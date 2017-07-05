<?php 

namespace Kabas\Utils;

use Kabas\App;

class Options {

    public static function __callStatic($name, $args)
    {
        $field = $args[0];
        return App::content()->options->load($name)->$field;
    }

}