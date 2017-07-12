<?php

namespace Kabas\Database\Json\Runners\Operators;

class IsGreaterThanOrEqualTo extends Operator implements OperatorInterface
{
    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        $value = $this->toType($value);
        $expression = $this->prepare($this->expression);
        if($this->getType() == 'date') return $value->gte($expression);
        return ($value >= $expression);
    }
}