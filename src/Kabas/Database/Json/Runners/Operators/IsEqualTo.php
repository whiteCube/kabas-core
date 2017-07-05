<?php

namespace Kabas\Database\Json\Runners\Operators;

use Kabas\Database\Json\Runners\Concerns\HasSimpleExpression;

class IsEqualTo implements OperatorInterface
{
    use HasSimpleExpression;

    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        return ($this->secureExpression($value) == $this->expression);
    }
}