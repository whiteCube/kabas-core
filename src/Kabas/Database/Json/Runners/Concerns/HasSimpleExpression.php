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
        $this->expression = $expression;
    }
}