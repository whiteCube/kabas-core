<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Item;

class Tel extends Item
{

    public function __toString()
    {
        return $this->output->label;
    }

    public function condition()
    {
        return (is_object($this->value) 
            && is_string($this->value->label) 
            && is_string($this->value->href) 
            && preg_match('/^([0-9\.\s\/\+\-]+)$/', $this->value->href) === 1);
    }

    public function __get($key)
    {
        return $this->output->$key;
    }

    public function parse($value)
    {
        $value->href = str_replace(['.', '/', ' ', '-'], '', trim(strip_tags(strval($value->href))));
        return $value;
    }

    /**
     * Formats a raw value in order and makes it usable for said field type
     * @param mixed $value
     * @return string
     */
    public static function format($value)
    {
        if(is_string($value)) $value = json_decode($value);
        if(is_array($value)) $value = (object) $value;
        if(!is_object($value) || !isset($value->label) || !isset($value->href)) return false;
        $format = new \stdClass();
        $format->label = trim($value->label);
        $format->href = isset($value->href) ? $value->href : '';
        return $format;
    }

}
