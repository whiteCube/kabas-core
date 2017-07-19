<?php

namespace Kabas\Fields;

class Option
{
    protected $key;

    protected $label;

    protected $selected;

    public function __construct($key, $label, $parseKey = false)
    {
        $this->key = $key;
        $this->label = trim($label);
    }

    public function __toString()
    {
        return (string) $this->label;
    }

    /**
     * Get option's label
     * @return string
     */
    public function label()
    {
        return $this->label;
    }

    /**
     * Get option's orignal key
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get option's selected state
     * @return bool
     */
    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * Sets selected state
     * @param  bool $status
     * @return bool
     */
    public function setSelected($status)
    {
        return $this->selected = $status ? true : false;
    }

    /**
     * Checks if given value matches with key or label
     * @param  mixed $value
     * @return bool
     */
    public function matches($value)
    {
        if($value === $this->key) return true;
        if($value === $this->label) return true;
        return false;
    }

}
