<?php

namespace Kabas\Fields\Types;

use Kabas\App;
use Kabas\Fields\Repeatable;

class Repeater extends Repeatable
{

    /**
     * The default type for inner fields
     * @var string
     */
    const DEFAULT = 'text';

    /**
     * Makes an array of defined groups
     * @param  array $value
     * @return array
     */
    protected function parse($value)
    {
        $a = [];
        if(!is_array($value)) return $a;
        $class = App::fields()->getClass($this->option->type ?? static::DEFAULT);

        foreach ($value as $i => $item) {
            $a[] = new $class($this->getMultiFieldname($i), $item, $this->option);
        }
        
        return $a;
    }


    /**
     * Sets options & other field data
     * @return array
     */
    protected function implement($structure)
    {
        parent::implement($structure);
        if(!isset($this->reference)) $this->setOptions($structure->option);
    }

}
