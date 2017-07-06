<?php

namespace Kabas\Database\Json\Runners\Operators;

use Kabas\Database\Json\Runners\Operators\Expressions\Like;

class IsLike implements OperatorInterface
{
    protected $expression;

    /**
     * Makes a new "LIKE" Operator
     * @param string $expression
     * @return void
     */
    public function __construct($expression) {
        $this->expression = $this->makeRegexFromExpression($expression);
    }

    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        return (bool) preg_match($this->expression->toRegex(), $value);
    }

    /**
     * Returns an instance of the "LIKE" expression parser
     * @param string $expression
     * @return Kabas\Database\Json\Runners\Operators\Expressions\Like
     */
    protected function makeRegexFromExpression($expression)
    {
        return new Like($expression);
    }
}