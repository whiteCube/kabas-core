<?php

namespace Kabas\Fields\Types;

use Kabas\App;
use Kabas\Fields\Uploadable;
use Kabas\Objects\Image\Item as ImageObject;

class Image extends Uploadable
{

    /**
     * Formats a raw value in order and makes it usable for said field type
     * @param mixed $value
     * @return string
     */
    public static function format($value)
    {
        if(is_string($value)) $value = json_decode($value);
        if(is_array($value)) $value = (object) $value;
        if(!is_object($value) || !isset($value->path)) return false;
        $format = new \stdClass();
        $format->path = trim($value->path);
        $format->alt = isset($value->alt) ? trim(strip_tags(strval($value->alt))) : '';
        return $format;
    }

    /**
     * Condition to check if the value is correct for this field type.
     * @return bool
     */
    public function condition()
    {
        return true;
    }

    public function __call($name, $args)
    {
        if(!$this->output) return false;
        return call_user_func_array([$this->output, $name], $args);
    }

    /**
     * Makes an Image instance from value
     * @param  mixed $value
     * @return object
     */
    protected function parse($value)
    {
        if(!is_object($value)) return false;
        return new ImageObject($value);
    }

}