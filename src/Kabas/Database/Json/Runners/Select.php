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
        return $this->loadModelCache($this->getLocale(), $this->hasOtherWheresThanKey())
            ->applyLimit()
            ->toData();
    }

    /**
     * Checks if the query should perform any
     * other where statement than on the primary key
     * @return boolean
     */
    protected function hasOtherWheresThanKey()
    {
        if(!$this->wheres) return false;
        // TODO : check where statements
        return true;
    }

    protected function getLocale()
    {
        // TODO : define locale from query ?
        return Lang::getOrDefault()->original;
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