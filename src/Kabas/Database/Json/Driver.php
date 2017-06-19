<?php

namespace Kabas\Database\Json;

use Kabas\Drivers\DriverInterface;
use Kabas\Config\Language;
use Kabas\Model\Model;

class Driver implements DriverInterface
{
    /**
    * The referenced model interface
    * @var object
    */
    protected $model;

    /**
    * Default locale to look for
    * @var string
    */
    protected $locale;

    public function __construct(ModelInterface $model, Language $locale)
    {
        $this->model = $model;
        $this->setLocale($locale);
    }

    /**
     * Defines locale for single-locale-related JSON queries
     * @param string $key
     * @return object|null
     */
    public function setLocale(Language $locale)
    {
        $this->locale = $locale->original;
    }

    /**
     * Returns a new empty Query based on given method
     * @param string $method
     * @param array $arguments
     * @return Kabas\Drivers\Json\Query
     */
    public function makeNewQuery(string $method, array $arguments = [])
    {
        $query = new Query($this->model, $this->locale);
        return call_user_func_array([$query, $method], $arguments);
    }

    /**
     * Returns a Query based on given model item and method
     * @param Kabas\Model\Model $model
     * @param string $method
     * @param array $arguments
     * @return Kabas\Drivers\Json\Query
     */
    public function makeModelQuery(Model $item, string $method, array $arguments = [])
    {
        $query = new Query($item, $this->locale);
        return call_user_func_array([$query, $method], $arguments);
    }

}
