<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Repeatable;

class Repeater extends Repeatable
{

    /**
     * Makes an array of defined groups
     * @param  array $value
     * @return array
     */
    protected function parse($value)
    {
        $a = [];
        if(!is_array($value)) return $a;
        $class = App::fields()->getClass($this->options->type);

        foreach ($value as $i => $item) {
            $a[] = new $class($this->getMultiFieldname($i), $item, $this->options);
        }
        
        return $a;
    }

}
