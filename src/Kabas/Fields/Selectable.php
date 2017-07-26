<?php

namespace Kabas\Fields;

use Kabas\Fields\Option;
use Kabas\Exceptions\TypeException;

class Selectable extends Item implements \IteratorAggregate
{
    public function __toString()
    {
        return implode(', ', $this->labels());
    }

    /**
     * Make a deep clone (options included)
     * @return void
     */
    public function __clone() {
        foreach ($this->options as $key => $option) {
            $this->options[$key] = clone $option;
        }
    }

    /**
     * Formats a raw value in order and makes it usable for said field type
     * @param mixed $value
     * @return string
     */
    public static function format($value)
    {
        if(is_string($value) && ($json = json_decode($value))) $value = $json;
        if(is_object($value)) $value = (array) $value;
        if(is_array($value)) return array_values($value);
        if(is_bool($value)) return $value ? 'true' : 'false';
        return trim(strval($value));
    }

    /**
     * Get all options
     * @return array
     */
    public function all()
    {
        return $this->options;
    }

    /**
     * Get an option
     * @param  string $value
     * @return Kabas\Fields\Option
     */
    public function get($value = null)
    {
        foreach ($this->options as &$option) {
            if($option->matches($value)) return $option;
        }
        return false;
    }

    /**
     * Gets all selected options
     * @return array
     */
    public function getSelected()
    {
        $selected = [];
        foreach($this->options as &$option) {
            if($option->isSelected()){
                $selected[] = $option;
            }
        }
        return $selected;
    }

    /**
     * Gets all labels from selected options
     * @return array
     */
    public function labels()
    {
        $labels = [];
        foreach ($this->getSelected() as $option) {
            $labels[] = $option->label();
        }
        return $labels;
    }

    /**
     * Gets label from first selected option
     * Usefull when not in multiple mode.
     * @return string
     */
    public function label()
    {
        return $this->labels()[0];
    }

    /**
     * Gets all keys from selected options
     * @return array
     */
    public function keys()
    {
        $keys = [];
        foreach ($this->getSelected() as $option) {
            $keys[] = $option->key();
        }
        return $keys;
    }

    /**
     * Gets key from first selected option
     * Usefull when not in multiple mode.
     * @return string
     */
    public function key()
    {
        return $this->keys()[0];
    }

    /**
     * Overrides options list
     * @param  array $options
     * @return void
     */
    public function setOptions($options = array())
    {
        $this->options = $this->makeOptions($options);
    }

    /**
     * Makes item iterable
     * @return array
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getSelected());
    }

    /**
     * Checks if all values have existing options
     * @return boolean
     */
    public function condition()
    {
        if($this->multiple && !is_array($this->value)) return false;
        if(is_array($this->value)) {
            foreach ($this->value as $value) {
                if(!$this->get($value)) return false;
            }
        }
        elseif(!$this->get($this->value)) return false;
        return true;
    }


    /**
     * Sets options & other field data
     * @return array
     */
    protected function implement($structure)
    {
        parent::implement($structure);
        $this->setMultiple($structure->multiple);
        $this->setOptions($structure->options);
    }

    /**
     * makes options from user defined list
     * @return array
     */
    protected function makeOptions($options)
    {
        if(!is_array($options) && !is_object($options)) throw new TypeException('Selectable field requires a valid options list.');
        $opts = [];
        $formatKeys = is_array($options) ? true : false;
        foreach($options as $key => $value){
            $opts[] = new Option($key, $value, $formatKeys);
        }
        return $opts;
        
    }

    /**
     * Redefines options select status
     * @param  mixed $value
     * @return mixed
     */
    protected function parse($value)
    {
        $this->resetSelect();
        if(!is_array($value)) return $this->parseValue($value);
        foreach ($value as $val) {
            $this->parseValue($val);
        }
    }

    protected function parseValue($value)
    {
        if($option = $this->get($value)){
            $option->setSelected(true);
        }
    }

    protected function resetSelect()
    {
        foreach ($this->options as $option) {
            $option->setSelected(false);
        }
    }
}
