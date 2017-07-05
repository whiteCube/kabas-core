<?php

namespace Kabas\Database\Json\Runners\Operators;

use Kabas\Database\Json\Runners\Operators\Expressions\Like;

class IsNotLike extends IsLike implements OperatorInterface
{
    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        return !parent::compare($value);
    }
}