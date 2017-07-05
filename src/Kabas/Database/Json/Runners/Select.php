<?php

namespace Kabas\Database\Json\Runners;

use Kabas\Utils\Lang;
use Kabas\Database\Json\Query;

class Select
{
    use Concerns\HasCache;

    /**
    * The queried items
    * @var array
    */
    protected $stack = [];

    /**
    * The query to execute
    * @var \Kabas\Database\Json\Query
    */
    protected $query;

    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    /**
     * Run the query as a "select" statement.
     * @return array
     */
    public function run()
    {
        return $this->loadModelCache()
            ->applyWheres()
            ->applyLimit()
            ->toData();
    }

    /**
     * Performs conditions to reduce current stack
     * @return this
     */
    protected function applyWheres()
    {
        if(is_null($this->query->wheres)) return $this;
        foreach ($this->query->wheres as $condition) {
            $this->stack = $this->applyCondition($condition, $this->stack);
        }
        return $this;
    }

    /**
     * Performs single condition on given stack
     * @param array $condition
     * @param array $stack
     * @return array
     */
    protected function applyCondition($condition, $stack)
    {
        return array_filter($stack, function($item) use ($condition) {
            $column = $this->getColumnValue($item, $condition['column']);
            return $this->runCondition($column, $condition['operator'], $condition['value']);
        });
    }

    /**
     * Tests if given argument applys to given value using given operator
     * @param string $argument
     * @param string $operator
     * @param string $value
     * @return bool
     */
    protected function runCondition($argument, $operator, $value)
    {
        switch ($operator) {
            //  TODO : all other available operators
            case '=': return ($argument == $value); break;
        }
    }

    /**
     * Returns the column's real value for given item
     * @param Kabas\Database\Json\Cache\Item $item
     * @param string $key
     * @return mixed
     */
    protected function getColumnValue($item, $key)
    {
        if($key === $this->query->getModel()->getQualifiedKeyName()) {
            return $item->key;
        }
        // TODO : apply locale on get()
        return $item->get()->data->{$key} ?? null;
    }

    /**
     * Slices current stack using the query's limit and offset
     * @return this
     */
    protected function applyLimit()
    {
        if(!$this->query->limit) return $this;
        $this->stack = array_slice($this->stack, $this->query->offset ?? 0, $this->query->limit, true);
        return $this;
    }

    /**
     * Transforms stack to data array
     * @return array
     */
    protected function toData()
    {
        return array_map(function($item) {
            // TODO : apply locale on toDataObject
            return $item->toDataObject($this->query->getModel()->getQualifiedKeyName());
        }, $this->stack);
    }
}