<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Groupable;

class Group extends Groupable
{

    /**
     * makes options from user defined list
     * @return array
     */
    protected function makeOptions($options)
    {
        $a = [];
        foreach(parent::makeOptions($options) as $name => $field){
            $item = new \stdClass();
            $item->name = $name;
            $item->class = App::fields()->getClass(isset($field->type) ? $field->type : 'text');
            $item->structure = $field;
            $a[] = $item;
        }
        return $a;
    }

    /**
     * Makes an array of defined fields
     * @param  object $value
     * @return array
     */
    protected function parse($value)
    {
        $a = [];
        foreach ($this->options as $field) {
            if($field->class){
                $key = $field->name;
                $val = isset($value->$key) ? $value->$key : null;
                $class = $field->class;
                $a[$key] = new $class($key, $val, $field->structure);
            }
        }
        return $a;
    }
}
