<?php

namespace Kabas\Database\Json\Runners\Operators;

use Kabas\Database\Json\Runners\Concerns\HasSimpleExpression;

class IsLessThan implements OperatorInterface
{
    use HasSimpleExpression;

    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        return ($value < $this->expression);
    }
}