<?php

namespace Kabas\Utils;

use Kabas\Utils\Debug\Backtrace;

class Debug {
    public static function backtrace($echo = true, $withFunctionArguments = true)
    {
        $items = debug_backtrace();
        array_shift($items);
        $backtrace = new Backtrace($items, $withFunctionArguments);
        if($echo) echo $backtrace->getOutput();
        return $backtrace;
    }
}
