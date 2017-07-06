<?php

namespace Kabas\Database\Json\Runners\Operators;

use Kabas\Utils\Text;
use Kabas\Database\Json\Runners\Operators\Expressions\Expression;
use Kabas\Database\Json\Runners\Exceptions\InvalidExpressionException;

abstract class Operator
{
    protected $expression;

    protected $type;

    /**
     * Makes a new simple Operator
     * @param string $expression
     * @param string $type
     * @return void
     */
    public function __construct($expression, $type) {
        $this->expression = $this->makeExpression($expression);
        $this->type = $type;
    }

    /**
     * Returns string representation of parsed expression
     * @return string
     */
    public function getExpressionString() {
        return $this->expression->toString();
    }

    /**
     * Returns a human readable name for current operator
     * @return string
     */
    public function getName() {
        return Text::removeNamespace(get_class($this));
    }

    /**
     * Returns the key/column type this operator should perform on
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Returns a basic expression instance
     * @param mixed $expression
     * @return Kabas\Database\Json\Runners\Operators\Expressions\Expression
     */
    protected function makeExpression($expression) {
        return new Expression($expression);
    }
    
    /**
     * Transforms given expression to key type syntax
     * @param Kabas\Database\Json\Runners\Operators\Expressions\Expression $expression
     * @throws Kabas\Database\Json\Runners\Exceptions\InvalidExpressionException
     * @return mixed
     */
    protected function prepare(Expression $expression) {
        try {
            $value = $expression->toType($this->getType());
        } catch (\Exception $e) {
            throw new InvalidExpressionException($this, null, $e);
        }
        return $value;
    }
}