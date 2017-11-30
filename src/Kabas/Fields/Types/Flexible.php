<?php

namespace Kabas\Fields\Types;

use Kabas\App;
use Kabas\Fields\Repeatable;

class Flexible extends Repeatable
{

    /**
    * makes options from user defined list
    * @return array
    */
    protected function makeOptions($options)
    {
        $a = [];
        foreach(parent::makeOptions($options) as $key => $field) {
            $item = new \stdClass();
            $item->class = App::fields()->getClass(isset($field->type) ? $field->type : 'text');
            $item->key = $key;
            $item->structure = $field;
            $a[] = $item;
        }
        return $a;
    }

    /**
    * Makes an array of defined groups
    * @param  array $value
    * @return array
    */
    protected function parse($value)
    {
        $a = [];
        if(!is_array($value)) return $a;
        foreach ($value as $i => $item) {
            if(!($option = $this->findOption($item->option))) continue;
            $class = $option->class;
            $field = new $class($this->getMultiFieldname($i), $item->value, $option->structure);
            $field->setFlexible($option->key);
            $a[] = $field;
        }
        return $a;
    }

    public function findOption($key)
    {
        foreach ($this->options as $option) {
            if($option->key === $key) return $option;
        }
        return false;
    }

}
