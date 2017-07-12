<?php

namespace Kabas\Database\Json\Runners\Operators;

use Kabas\Database\Json\Runners\Operators\Expressions\Like;

class IsLike extends Operator implements OperatorInterface
{
    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        return (bool) preg_match($this->expression->toRegex(), $this->prepare($this->expression));
    }

    /**
     * Returns an instance of the "LIKE" expression parser
     * @param string $expression
     * @return Kabas\Database\Json\Runners\Operators\Expressions\Like
     */
    protected function makeExpression($expression)
    {
        return new Like($expression);
    }
}