<?php

namespace Kabas\Database\Json\Runners\Conditions;

use Kabas\Database\Json\Runners\Concerns\HasBooleanChaining;

class Basic implements ConditionInterface
{
    use HasBooleanChaining;

    protected $query;

    protected $key;

    protected $operator;

    protected $value;
    
    /**
     * Makes a new simple Condition from given information
     * @param array $info
     * @return void
     */
    public function __construct($info) {
        $this->query = $info['query'];
        $this->key = $info['column'];
        $this->operator = $info['operator'];
        $this->value = $info['value'];
        $this->setBooleanMode($info['boolean']);
    }

    /**
     * Runs the conditions for given stack
     * @param array $stack
     * @param array|null $filtered
     * @return array
     */
    public function apply($stack, $filtered = null) : array {
        return $this->applyChaining($stack, $filtered);
    }

    /**
     * Performs the condition on given stack
     * @param array $stack
     * @return array
     */
    public function run($stack) : array {
        return array_filter($stack, function($item) {
            $argument = $this->getItemKeyValue($item, $this->key);
            return $this->checkCondition($argument, $this->operator, $this->value);
        });
    }

    /**
     * Tests if given argument applys to given value using given operator
     * @param string $argument
     * @param string $operator
     * @param string $value
     * @return bool
     */
    protected function checkCondition($argument, $operator, $value)
    {
        switch ($operator) {
            //  TODO : all other available operators
            case '=': return ($argument == $value); break;
            case 'LIKE': return $this->isLike($argument, $value); break;
        }
    }

    protected function isLike($value, $expression)
    {
        return true;
    }

    /**
     * Returns the key's value on given item
     * @param Kabas\Database\Json\Cache\Item $item
     * @param string $key
     * @return mixed
     */
    protected function getItemKeyValue($item, $key)
    {
        if($key === $this->query->getModel()->getQualifiedKeyName()) {
            return $item->key;
        }
        // TODO : apply locale on get()
        return $item->get()->data->{$key} ?? null;
    }
}