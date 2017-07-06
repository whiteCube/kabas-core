<?php

namespace Kabas\Database\Json\Runners\Concerns;

use Kabas\Database\Json\Runners\Exceptions\InvalidOperatorException;
use Kabas\Fields\Container as Fields;

trait HasOperator
{
    static protected $operators = [
        '=' => 'IsEqualTo',
        '<' => 'IsLessThan',
        '>' => 'IsGreaterThan',
        '<=' => 'IsLessThanOrEqualTo',
        '>=' => 'IsGreaterThanOrEqualTo',
        '<>' => 'IsDifferentFrom',
        '!=' => 'IsDifferentFrom',
        '<=>' => 'IsEqualTo',
        'like' => 'IsLike',
        'like binary' => 'IsCaseSensitivelyLike',
        'not like' => 'IsNotLike',
        'between' => 'IsBetween',
        'ilike' => 'IsCaseSensitivelyLike',
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
     * @param string $key
     * @return Kabas\Database\Json\Runners\Operators\OperatorInterface
     */
    protected function makeOperator($grammar, $expression, $key)
    {
        $operator = '\\Kabas\\Database\\Json\\Runners\\Operators\\' . $this->getOperatorName($grammar);
        return new $operator($expression, $this->getKeyType($key));
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

    /**
     * Returns the underlaying field type for given key
     * @param string $key
     * @return string
     */
    protected function getKeyType($key)
    {
        if($this->query->getModel()->getQualifiedKeyName() == $key) return Fields::KEY;
        return $this->query->getModel()->getRawField($key)->type ?? Fields::DEFAULT;
    }
}