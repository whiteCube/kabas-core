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
        return $this->loadModelCache($this->getLocale())
            ->applyWheres()
            ->applyLimit()
            ->toData();
    }

    /**
     * Returns the locale in which the query should perfom
     * @return string
     */
    protected function getLocale()
    {
        // TODO : define locale from query ?
        return Lang::getOrDefault()->original;
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
        $validated = [];
        foreach ($stack as $item) {
            $column = $this->getColumnValue($item, $condition['column']);
            if(!$this->runCondition($column, $condition['operator'], $condition['value'])) continue;
            $validated[] = $item;
        }
        return $validated;
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
            case '=': return ($argument == $value); break;
            //  TODO : all other available operators
            default: return; break;
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
        $data = [];
        foreach ($this->stack as $key => $item) {
            $data[] = $item->toDataObject($this->query->getModel()->getQualifiedKeyName());
        }
        return $data;
    }
}