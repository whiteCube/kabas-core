<?php

namespace Kabas\Fields\Types;

use \Carbon\Carbon;
use \Kabas\Fields\Item;

class Date extends Item
{

    /**
     * Condition to check if the value is correct for this field type.
     * @param  mixed $value
     * @return bool
     */
    public function condition()
    {
        return strtotime($this->output);
    }

    public function __get($key)
    {
        return $this->output->$key;
    }

    public function __set($key, $value)
    {
        if(!isset($this->output)) $this->output = new \stdClass;
        $this->output->$key = $value;
        return $this->output;
    }

    public function __call($method, $params)
    {
        return call_user_func_array([$this->output, $method], $params);
    }

    /**
     * Makes a Carbon instance from value
     * @param  mixed $value
     * @return mixed
     */
    protected function parse($value)
    {
        if(!$value) return null;
        return new Carbon($value);
    }

}
