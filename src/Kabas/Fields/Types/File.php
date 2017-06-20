<?php

namespace Kabas\Fields\Types;

use \Kabas\Fields\Item;
use \Kabas\Utils\Url as UrlUtil;

class File extends Item
{

    protected $reference;

    /**
     * Condition to check if path exitst
     * @return bool
     */
    public function condition()
    {
        if(is_object($this->reference)) return true;
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
        if(!$this->reference) return false;
        return $this->reference->url;
    }

    protected function setReference($value)
    {
        $this->reference = $this->getReference($value);
    }

    protected function getReference($value)
    {
        if(is_string($value) && file_exists($value)){
            $o = new \stdClass();
            $o->path = realpath($value);
            if($o->path){
                $path = pathinfo($o->path);
                $o->dirname = isset($path['dirname']) ? $path['dirname'] : null;
                $o->basename = isset($path['basename']) ? $path['basename'] : null;
                $o->filename = isset($path['filename']) ? $path['filename'] : null;
                $o->extension = isset($path['extension']) ? $path['extension'] : null;
                $o->size = filesize($o->path);
                $o->url = UrlUtil::fromPath($o->path);
                return $o;
            }
        }
        return false;
    }

}
