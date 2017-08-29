<?php

namespace Kabas\Fields\Types;

use Kabas\Fields\Uploadable;
use Kabas\Objects\Uploads\File as FileObject;

class File extends Uploadable
{
    /**
     * Condition to check if path exitst
     * @return bool
     */
    public function condition()
    {
        return true;
    }

    public function __get($key)
    {
        return $this->output->$key;
    }

    public function __call($name, $args)
    {
        if(!$this->output) return false;
        return call_user_func_array([$this->output, $name], $args);
    }

    /**
     * Makes reference object and stores full path
     * @param  mixed $value
     * @return mixed
     */
    protected function parse($value)
    {
        if(!is_string($value)) return;
        return new FileObject($value);
    }

}
