<?php

namespace Kabas\Database\Json\Runners\Operators;

use Carbon\Carbon;
use Kabas\Database\Json\Runners\Exceptions\InvalidExpressionException;

class IsBetween extends Operator implements OperatorInterface
{
    protected $expression;

    protected $type;

    /**
     * Makes a new "BETWEEN" Operator
     * @param array $expression
     * @return void
     */
    public function __construct(array $expression) {
        $this->expression = $this->cleanupExpression($expression);
        $this->type = $this->guessType();
    }

    /**
     * Tests if given value is equal to this expression
     * @param mixed $value
     * @return bool
     */
    public function compare($value) : bool {
        $value = $this->castValue($value);
        if($this->type == 'date') {
            if(!is_a($value, Carbon::class)) return false;
            return ($value->gte($this->expression[0]) && $value->lte($this->expression[1]));
        }
        return ($value >= $this->expression[0] && $value <= $this->expression[1]);
    }

    /**
     * Cleans array of expressions for between use.
     * @param array $values
     * @return array
     */
    protected function cleanupExpression($values) {
        return array_map(function($expression) {
            return $this->castValue($expression);
        }, array_replace([null, null], array_slice(array_values($values), 0, 2)));
    }

    /**
     * Returns the type of comparaison we shold perform
     * @return string
     */
    protected function guessType() {
        $type = $this->getType($this->expression[0]);
        if($type != $this->getType($this->expression[1]) || $type == 'NULL') {
            throw new InvalidExpressionException($this);
        }
        return $type;
    }

    /**
     * Returns the type of given expression
     * @param mixed $value
     * @return string
     */
    protected function getType($value) {
        if(is_null($value)) return 'NULL';
        if(is_object($value) && is_a($value, Carbon::class)) return 'date';
        return 'regular';
    }

    /**
     * Returns string representation of array expression
     * @return string
     */
    public function getExpressionString() {
        return $this->getName() . ' ' . ($this->expression[0] ?? 'NULL') . ' AND ' . ($this->expression[1] ?? 'NULL');
    }
}