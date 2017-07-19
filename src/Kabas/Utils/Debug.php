<?php

namespace Kabas\Utils;

use Kabas\Utils\Debug\Backtrace;

class Debug {
    public static function backtrace($echo = true, $withArguments = true)
    {
        if(!DEBUG) return false;
        $items = debug_backtrace();
        array_shift($items);
        $backtrace = new Backtrace($items, $withArguments);
        if($echo) echo $backtrace->getOutput();
        return $backtrace;
    }
}
