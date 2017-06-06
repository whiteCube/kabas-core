<?php

namespace Kabas\Utils;

use \Kabas\App;

class Part
{
    /**
     * Get and display the part with the corresponding ID onto the page.
     * @param  string $part
     * @param  array $params (optionnal)
     * @return void
     */
    static function get($part, $params = [])
    {
        $part = App::content()->partials->load($part);
        if($part){
            $part->set($params);
            $part->make();
        }
    }

    static function __callStatic($method, $params)
    {
        if(!empty($params)) $params = $params[0];
        self::get($method, $params);
    }
}
