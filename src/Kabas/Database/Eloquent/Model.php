<?php

namespace Kabas\Database;

use Kabas\Database\ModelInterface;
use Kabas\Database\Model as BaseModel;

class Model extends BaseModel implements ModelInterface
{
    /**
     * assign table name from static repository attribute
     * @param  array  $attributes
     */
    public function __construct($attributes = [])
    {
        if($this->table && !static::$repository) {
            static::$repository = $this->table;
        }
        parent::__construct($attributes);
    }
}
