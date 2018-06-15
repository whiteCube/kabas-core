<?php

namespace Kabas\Fields;

use Kabas\Exceptions\TypeException;

class Aggregate extends Item implements \IteratorAggregate, \Countable 
{
    public function __toString()
    {
        return '#multiple-fields';
    }

    /**
     * Formats a raw value in order and makes it usable for said field type
     * @param mixed $value
     * @return string
     */
    public static function format($value)
    {
        if(is_null($value) || (is_string($value) && !strlen($value))) return;
        if(is_string($value) && ($json = json_decode($value))) return $json;
        if(is_array($value) || is_object($value)) return $value;
        return false;
    }

    /**
     * Sets options & other field data
     * @return array
     */
    protected function implement($structure)
    {
        parent::implement($structure);
        if(!isset($this->reference)) $this->setOptions($structure->options);
    }

    /**
     * Overrides options list
     * @param  object $options
     * @return void
     */
    public function setOptions($options = array())
    {
        $this->options = $this->makeOptions($options);
    }

    /**
     * Overrides one of the contained fields
     * @param  string $name
     * @param  mixed $value
     * @return this
     */
    public function setOption($key = null, $value)
    {
        if(!isset($this->output[$key])) return $this;
        $this->output[$key] = $value;
        return $this;
    }

    /**
     * makes options from user defined list
     * @return array
     */
    protected function makeOptions($options)
    {
        if(!is_array($options) && !is_object($options)) throw new TypeException('Field groups require a valid fields list.');
        return $options;
    }

    /**
     * Makes item iterable
     * @return array
     */
    public function getIterator()
    {
        if(is_null($this->output)) $this->output = [];
        return new \ArrayIterator($this->output);
    }

    /**
     * Make item countable
     * @return int
     */
    public function count() {
        return count($this->output);
    }

    /**
     * Retrieves one or all of the contained fields
     * @param  string $name
     * @return object
     */
    public function get($key = null)
    {
        if(is_null($key)) return $this->output;
        if(isset($this->output[$key])) return $this->output[$key];
        return null;
    }

    /**
     * Gets an amount of random items
     * @param integer $amount
     * @return array
     */
    public function random($amount = 1)
    {
        $items = [];
        $indexes = array_rand($this->output, $amount);
        if(!is_array($indexes)) $indexes = [$indexes];
        for($i = 0; $i < count($indexes); $i++) {
            $items[] = $this->output[$indexes[$i]];
        }
        return $items;
    }
}
