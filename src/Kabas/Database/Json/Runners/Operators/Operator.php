<?php

namespace Kabas\Database\Json\Runners\Operators;

use Carbon\Carbon;
use Kabas\Utils\Text;

abstract class Operator
{
    protected $expression;

    /**
     * Makes a new simple Operator
     * @param string $expression
     * @return void
     */
    public function __construct($expression) {
        $this->expression = $this->castValue($expression);
    }

    /**
     * Returns string representation of parsed expression
     * @return string
     */
    public function getExpressionString() {
        return (string) $this->expression;
    }

    /**
     * Returns a human readable name for current operator
     * @return string
     */
    public function getName() {
        return Text::removeNamespace(get_class($this));
    }

    /**
     * Transforms value to usable values if needed.
     * @param mixed $value
     * @return mixed
     */
    protected function castValue($value) {
        $value = $this->castNullStringToNull($value);
        return $this->castDateStringToDate($value);
    }

    /**
     * Transforms "null" strings to real null values if needed.
     * @param mixed $value
     * @return mixed
     */
    protected function castNullStringToNull($value) {
        if(!is_string($value)) return $value;
        if(strtolower($value) == 'null') return null;
        return $value;
    }

    /**
     * Transforms date strings to Carbon instances if needed.
     * @param mixed $value
     * @return mixed
     */
    protected function castDateStringToDate($value) {
        if(!is_string($value)) return $value;
        if(strlen($value) < 2) return $value;
        try {
            $date = Carbon::parse($value);
        } catch (\Exception $e) {
            return $value;
        }
        return $date;
    }
}