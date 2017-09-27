<?php

namespace Kabas\Database\Eloquent;

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
        if(isset($this->table) && !isset($this->repository)) {
            $this->repository = $this->table;
        }
        parent::__construct($attributes);
    }
}
