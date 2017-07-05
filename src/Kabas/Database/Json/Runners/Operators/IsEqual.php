<?php

namespace Kabas\Database\Json\Runners\Operators;

class IsEqual implements OperatorInterface
{
    protected $expression;

    /**
     * Makes a new "=" Operator
     * @param string $expression
     * @return void
     */
    public function __construct($expression) {
        $this->expression = $expression;
    }

    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        return ($value == $this->expression);
    }
}