<?php

namespace Kabas\Database\Json\Runners;

use Kabas\Utils\Lang;
use Kabas\Database\Json\Query;
use Kabas\Database\Json\Runners\Conditions\Nested as NestedCondition;

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
            ->applyOrder()
            ->applyLimit()
            ->toData();
    }

    /**
     * Performs conditions to reduce current stack
     * @return this
     */
    protected function applyWheres()
    {
        $conditions = new NestedCondition(['query' => $this->query, 'boolean' => 'AND']);
        $this->stack = $conditions->apply($this->stack);
        return $this;
    }

    /**
     * Reorders current stack using the query's order
     * statement or key (filename) if not defined
     * @return this
     */
    protected function applyOrder()
    {
        if(!$this->query->orders) {
            ksort($this->stack);
            return $this;
        }
        // TODO : real sorting on the $this->query->orders array
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