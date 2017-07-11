<?php

namespace Kabas\Database\Json\Runners\Operators;

class IsBetween extends Operator implements OperatorInterface
{
    /**
     * Makes a new "BETWEEN" Operator
     * @param array $expression
     * @param string $type
     * @return void
     */
    public function __construct(array $expression, $type) {
        parent::__construct($expression, $type);
    }

    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        $value = $this->prepare(parent::makeExpression($value));
        if($this->getType() == 'date') {
            return ($value->gte($this->minimum()) && $value->lte($this->maximum()));
        }
        return ($value >= $this->minimum() && $value <= $this->maximum());
    }

    /**
     * Returns string representation of array expression
     * @return string
     */
    public function getExpressionString() {
        return 'BETWEEN ' . implode(' AND ', array_map(function($expression) {
            return $expression->toType($this->type, true);
        }, $this->expression));
    }

    /**
     * Returns a basic expression instance
     * @param $values
     * @return array
     */
    protected function makeExpression($values) {
        return array_map(function($expression) {
            return parent::makeExpression($expression);
        }, array_replace([null, null], array_slice(array_values($values), 0, 2)));
    }

    /**
     * Returns first expression prepared for key type
     * @return mixed
     */
    protected function minimum() {
        return $this->prepare($this->expression[0]);
    }

    /**
     * Returns second expression prepared for key type
     * @return mixed
     */
    protected function maximum() {
        return $this->prepare($this->expression[1]);
    }
}