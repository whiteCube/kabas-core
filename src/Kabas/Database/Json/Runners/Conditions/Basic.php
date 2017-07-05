<?php

namespace Kabas\Database\Json\Runners\Conditions;

use Kabas\Database\Json\Runners\Concerns\HasOperator;
use Kabas\Database\Json\Runners\Concerns\HasBooleanChaining;

class Basic implements ConditionInterface
{
    use HasOperator;
    use HasBooleanChaining;

    protected $query;

    protected $key;

    protected $operator;
    
    /**
     * Makes a new simple Condition from given information
     * @param array $info
     * @return void
     */
    public function __construct($info) {
        $this->query = $info['query'];
        $this->key = $info['column'];
        $this->operator = $this->makeOperator($info['operator'], $info['value']);
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
            return $this->operator->compare($this->getItemKeyValue($item, $this->key));
        });
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