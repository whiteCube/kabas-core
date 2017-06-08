<?php

namespace Kabas\Model;

use Kabas\App;
use Kabas\Utils\Text;

class Model
{
    /**
    * The current model's singular name
    * @var string
    */
    protected $object;

    /**
    * The current model's table or directory name
    * @var string
    */
    protected $repository;

    /**
    * The current model's structure file
    * @var string
    */
    protected $structure;

    /**
    * The current model's fillable fields
    * @var string
    */
    protected $fillable = [];

    /**
    * The current model's guarded fields
    * @var string
    */
    protected $guarded = [];

    /**
    * The current model's driver instance
    * @var object
    */
    private $driver;

    public function __construct()
    {
        $this->object = $this->object ?? $this->generateObjectName();
        $this->repository = $this->repository ?? $this->generateRepositoryName();
        $this->structure = $this->generateStructurePath($this->structure);
        $this->driver = $this->makeDriverInstance();
    }

    public function __set($name, $value)
    {
        $this->driver->$name = $value;
    }

    public function __call($name, $arguments = [])
    {
        return call_user_func_array([$this->driver, $name], $arguments);
    }

    public static function __callStatic($name, $arguments = [])
    {
        $instance = new static();
        return call_user_func_array([$instance->driver, $name], $arguments);
    }

    /**
     * Returns the current model's object name
     * @return string
     */
    public function getObjectName() : string
    {
        return $this->object;
    }

    /**
     * Returns the current model's repository name
     * @return string
     */
    public function getRepository() : string
    {
        return $this->repository;
    }

    /**
     * Returns the current model's full path to its JSON structure file
     * @return string
     */
    public function getStructurePath() : string
    {
        return $this->structure;
    }

    /**
     * Guesses the model's object name based on class name
     * @return string
     */
    protected function generateObjectName()
    {
        return lcfirst(Text::removeNamespace(get_class($this)));
    }

    /**
     * Guesses the model's repository name based on class name
     * @return string
     */
    protected function generateRepositoryName()
    {
        return $this->object . 's';
    }

    /**
     * Gets the full path to the model's structure JSON file
     * @return string
     */
    protected function generateStructurePath($file = null)
    {
        if(is_null($file)) $file = $this->object . '.json';
        $path = realpath(THEME_PATH . DS . 'structures' . DS . 'models' . DS . $file);
        if(!$path) throw new \Kabas\Exceptions\FileNotFoundException($file);
        return $path;
    }
}
