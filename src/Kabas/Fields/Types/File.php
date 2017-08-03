<?php

namespace Kabas\Fields\Types;

use Kabas\Fields\Uploadable;
use Kabas\Utils\Url as UrlUtil;

class File extends Uploadable
{

    protected $reference;

    /**
     * Condition to check if path exitst
     * @return bool
     */
    public function condition()
    {
        if($this->reference) return true;
        return false;
    }

    public function __get($key)
    {
        if(isset($this->reference->$key)) return $this->reference->$key;
        return false;
    }

    public function __call($method, $params)
    {
        return $this->__get($method);
    }

    /**
     * Makes reference object and stores full path
     * @param  mixed $value
     * @return mixed
     */
    protected function parse($value)
    {
        $this->setReference($value);
        if(is_object($this->reference)) return $this->reference->url;
        return null;
    }

    protected function setReference($value)
    {
        $this->reference = $this->getReference($value);
    }

    protected function getReference($value)
    {
        if(!is_null($value) && !is_string($value)) return false;
        // there was a wrong or empty value, returning true will
        // still let the type condition validate correctly.
        if(!($path = $this->getValuePath($value))) return true;
        // The field contains a valid path, we'll build the
        // reference object.
        $o = new \stdClass();
        $o->path = $path;
        $path = pathinfo($o->path);
        $o->dirname = isset($path['dirname']) ? $path['dirname'] : null;
        $o->basename = isset($path['basename']) ? $path['basename'] : null;
        $o->filename = isset($path['filename']) ? $path['filename'] : null;
        $o->extension = isset($path['extension']) ? $path['extension'] : null;
        $o->size = filesize($o->path);
        $o->url = UrlUtil::fromPath($o->path);
        return $o;
    }

    protected function getValuePath($value)
    {
        if(!$value) return false;
        $path = realpath($value);
        return $path ? $path : realpath(PUBLIC_PATH . DS . trim($value, DS));
    }

}
