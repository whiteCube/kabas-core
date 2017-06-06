<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Item;

class Number extends Item
{
    protected $type = "number";

    /**
     * Condition to check if the value is correct for this field type.
     * @param  mixed $value
     * @return bool
     */
    public function condition()
    {
        return is_numeric($this->value);
    }

    public function __get($key)
    {
        return $this->convert($key);
    }

    public function __call($method, $args)
    {
        return $this->convert($method);
    }

    /**
     * Updates output by adding the given value
     * @param  mixed $number
     * @return $this
     */
    public function add($number)
    {
        $this->output = ($this->output + $this->parse($number));
        return $this;
    }

    /**
     * Updates output by substracting the given value
     * @param  mixed $number
     * @return $this
     */
    public function subtract($number)
    {
        $this->output = ($this->output - $this->parse($number));
        return $this;
    }

    /**
     * Updates output by dividing its value with the given value
     * @param  mixed $number
     * @return $this
     */
    public function divide($number)
    {
        $this->output = ($this->output / $this->parse($number));
        return $this;
    }

    /**
     * Updates output by multiplying its value with the given value
     * @param  mixed $number
     * @return $this
     */
    public function multiply($number)
    {
        $this->output = ($this->output * $this->parse($number));
        return $this;
    }

    /**
     * Updates output with ceil()
     * @return $this
     */
    public function ceil()
    {
        $this->output = ceil($this->output);
        return $this;
    }

    /**
     * Updates output with floor()
     * @return $this
     */
    public function floor()
    {
        $this->output = floor($this->output);
        return $this;
    }

    /**
     * Converts value to new numeric type
     * @param  string $key
     * @return int | float
     */
    protected function convert($key)
    {
        switch (strtolower($key)) {
            case 'i':
            case 'int':
            case 'toint':
            case 'integer':
            case 'parseint':
            case 'intval':
                return intval($this->output);
                break;
            case 'f':
            case 'float':
            case 'tofloat':
            case 'parsefloat':
            case 'floatval':
                return floatval($this->output);
                break;
        }
        return false;
    }

    /**
     * Makes an output value (int/float) from value
     * @param  mixed $value
     * @return mixed
     */
    protected function parse($value)
    {
        if(!is_numeric($value)) return 0;
        if(is_string($value)){
            $value = str_replace([' ',','], ['','.'], $value);
            if(strpos($value, '.') !== false) return floatval($value);
            return intval($value);
        }
        return $value;
    }

}
