<?php

namespace Kabas\Fields;

use Kabas\Fields\Option;
use Kabas\Exceptions\TypeException;

class Selectable extends Item implements \IteratorAggregate
{
    public function __toString()
    {
        return strval($this->key());
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
        if(!($labels = $this->labels())) return null;
        return $labels[0];
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
     * @param  mixed $options
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
     * @param  mixed $options
     * @return array
     */
    protected function makeOptions($options)
    {
        if(is_string($options)) $options = $this->getOptionsFromStaticMethod($options);
        if(!is_array($options) && !is_object($options)) throw new TypeException('Selectable field requires a valid options list.');
        $opts = [];
        $formatKeys = is_array($options) ? true : false;
        foreach($options as $key => $value){
            $opts[] = new Option($key, $value, $formatKeys);
        }
        return $opts;
        
    }

    /**
     * Tries to retrieve an array of options from given static call
     * @param  string $staticMethodName
     * @return array|null
     */
    protected function getOptionsFromStaticMethod($staticMethodName)
    {
        if(!is_callable($staticMethodName, false, $staticMethodName)) return;
        if(!is_array($result = call_user_func_array($staticMethodName, [$this->name]))) return;
        return $result;
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
