<?php

namespace Kabas\Database\Json\Runners\Conditions;

use Kabas\Database\Json\Runners\Concerns\HasBooleanChaining;

class Nested implements ConditionInterface
{
    use HasBooleanChaining;

    protected $conditions = [];

    protected $query;

    /**
     * Makes a new Nested Condition from given information
     * @param array $info
     * @return void
     */
    public function __construct($info) {
        $this->query = $info['query'];
        $this->setBooleanMode($info['boolean']);
        $this->extractConditions($this->query);
    }

    /**
     * Runs the conditions for given stack
     * @param array $stack
     * @param array|null $filtered
     * @return array
     */
    public function apply($stack, $filtered = null) : array {
        if(!$this->conditions) return $filtered ?? $stack;
        return $this->applyChaining($stack, $filtered);
    }

    /**
     * Fills conditions array with condition instances
     * from given subquery.
     * @param \Kabas\Database\Json\Query $query
     * @return void
     */
    protected function extractConditions($query) {
        if(is_null($query->wheres)) return;
        foreach ($query->wheres as $i => $condition) {
            $this->conditions[] = $this->makeCondition($condition);
        }
    }

    /**
     * Instantiates a new condition
     * @param array $info
     * @return \Kabas\Database\Json\Runners\Conditions\ConditionInterface
     */
    protected function makeCondition($info) {
        $info = $this->alterConditionInfo($info);
        $condition = '\\Kabas\\Database\\Json\\Runners\\Conditions\\' . $info['type'];
        return new $condition($info);
    }

    /**
     * Transforms Eloquent condition information to usable
     * JSON Driver condition information.
     * @param array $info
     * @return array
     */
    protected function alterConditionInfo($info) {
        if(!isset($info['query'])) $info['query'] = $this->query;
        return $info;
    }

    /**
     * Filters given stack for all registered conditions
     * @param array $stack
     * @return array
     */
    public function run($stack) : array {
        foreach ($this->conditions as $condition) {
            $filtered = $condition->apply($stack, $filtered ?? null);
        }
        return $filtered;
    }
}