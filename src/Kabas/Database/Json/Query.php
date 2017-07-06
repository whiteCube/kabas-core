<?php

namespace Kabas\Database\Json;

use Kabas\Database\Json\Runners\Select;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Processors\Processor;

class Query extends QueryBuilder
{
    /**
    * The current model to query on
    * @var object
    */
    protected $model;

    /**
     * Create a new query builder instance.
     * @param  \Kabas\Database\Json\Model  $model
     * @param  \Illuminate\Database\Query\Processors\Processor  $processor
     * @return void    
     */
    public function __construct(Model $model, Processor $processor = null)
    {
        $this->model = $model;
        $this->processor = $processor;
    }

    /**
     * Returns the current query model
     * @return \Kabas\Database\Json\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Run the query as a "select" statement
     * @return array
     */
    protected function runSelect()
    {
        $select = new Select($this);
        return $select->run();
    }

    /**
     * Determine if the given operator is supported.
     * @param  string  $operator
     * @return bool
     */
    protected function invalidOperator($operator)
    {
        return !in_array(strtolower($operator), $this->operators, true);
    }

    /**
     * Add a where between statement to the query.
     *
     * @param  string  $column
     * @param  array   $value
     * @param  string  $boolean
     * @param  bool  $not
     * @return $this
     */
    public function whereBetween($column, array $value, $boolean = 'and', $not = false)
    {
        $type = 'Basic';
        $operator = 'between';
        $this->wheres[] = compact('column', 'type', 'operator', 'value', 'boolean', 'not');
        return $this;
    }
}