<?php

namespace Kabas\Database\Json\Runners\Concerns;

use Kabas\Database\Json\Runners\Exceptions\InvalidOperatorException;

trait HasOperator
{
    static protected $operators = [
        '=' => 'IsEqualTo',
        '<' => 'IsLessThan',
        '>' => 'IsGreaterThan',
        '<=' => null,
        '>=' => null,
        '<>' => 'IsDifferentFrom',
        '!=' => 'IsDifferentFrom',
        '<=>' => null,
        'like' => 'IsLike',
        'like binary' => null,
        'not like' => 'IsNotLike',
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
        $grammar = strtolower($grammar);
        if(!isset(static::$operators[$grammar])) {
            throw new InvalidOperatorException($grammar);
        }
        return static::$operators[$grammar];
    }
}