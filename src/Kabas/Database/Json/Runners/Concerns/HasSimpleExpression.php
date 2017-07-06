<?php

namespace Kabas\Database\Json\Runners\Concerns;

trait HasSimpleExpression
{
    protected $expression;

    /**
     * Makes a new simple Operator
     * @param string $expression
     * @return void
     */
    public function __construct($expression) {
        $this->expression = $this->secureExpression($expression);
    }

    /**
     * Transforms "null" expression to null
     * @param string $expression
     * @return mixed
     */
    protected function secureExpression($expression) {
        if(!is_string($expression)) return $expression;
        if(strtolower($expression) == 'null') return null;
        return $expression;
    }
}