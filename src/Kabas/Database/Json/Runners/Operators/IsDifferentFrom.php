<?php

namespace Kabas\Database\Json\Runners\Operators;

class IsDifferentFrom extends Operator implements OperatorInterface
{
    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        return ($this->castNullStringToNull($value) != $this->expression);
    }
}