<?php

namespace Kabas\Database\Json\Runners\Concerns;

trait HasOperator
{
    static protected $operators = [
        '=' => 'IsEqual',
        '<' => null,
        '>' => null,
        '<=' => null,
        '>=' => null,
        '<>' => null,
        '!=' => null,
        '<=>' => null,
        'like' => null,
        'like binary' => null,
        'not like' => null,
        'between' => null,
        'ilike' => null,
        '&' => null,
        '|' => null,
        '^' => null,
        '<<' => null,
        '>>' => null,
        'rlike' => null,
        'regexp' => null,
        'not regexp' => null,
        '~' => null,
        '~*' => null,
        '!~' => null,
        '!~*' => null,
        'similar to' => null,
        'not similar to' => null,
        'not ilike' => null,
        '~~*' => null,
        '!~~*' => null
    ];

    /**
     * Instantiates an operator for given SQL operator and expression
     * @param string $grammar
     * @param string $expression
     * @return Kabas\Database\Json\Runners\Operators\OperatorInterface
     */
    protected function makeOperator($grammar, $expression)
    {
        $operator = '\\Kabas\\Database\\Json\\Runners\\Operators\\' . $this->getOperatorName($grammar);
        return new $operator($expression);
    }

    /**
     * Transforms an SQL operator into the driver's operator name
     * @param string $grammar
     * @return string
     */
    protected function getOperatorName($grammar)
    {
        if(!isset(static::$operators[$grammar])) {
            // TODO : NotAJsonOperator or something similar
            throw new Exception("Error Processing Request", 1);
        }
        return static::$operators[$grammar];
    }
}