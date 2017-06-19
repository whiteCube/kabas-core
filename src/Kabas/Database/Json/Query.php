<?php

namespace Kabas\Database\Json;

use Kabas\Model\Model;

class Query
{
    /**
    * Items container
    * @var array
    */
    protected $container = [];

    /**
    * All loaded items (only available during some requests)
    * @var array|null
    */
    protected $cache;

    /**
    * Operation type container
    * @var object
    */
    protected $operation;

    /**
    * Active "where" conditions
    * @var array
    */
    protected $conditions;

    /**
    * Active limit
    * @var object|null
    */
    protected $limit;

    /**
    * Active ordering method
    * @var object|null
    */
    protected $order;

    /**
    * Model item to perform query on
    * @var object
    */
    protected $model;

    /**
    * Locale directory name
    * @var string
    */
    protected $locale;

    public function __construct(Model $model, string $locale)
    {
        $this->model = $model;
        $this->locale = $locale;
    }

    public function get()
}
