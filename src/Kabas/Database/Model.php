<?php

namespace Kabas\Database;

use Kabas\App;
use Kabas\Utils\Text;
use Kabas\Utils\File;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel
{
    /**
    * The current model's singular name
    * @var string
    */
    static protected $object;

    /**
    * The current model's table or directory name
    * @var string
    */
    static protected $repository;

    /**
    * The current model's structure file
    * @var string
    */
    static protected $structure;

    /**
    * The current model's defined fields
    * @var object
    */
    static protected $fields;

    /**
     * The "booting" method of the model.
     * @return void
     */
    protected static function boot()
    {
        $instance = new static();
        $instance->constructObjectName();
        $instance->constructRepositoryName();
        $instance->constructStructureFileName();
        parent::boot();
    }

    public function __set($name, $value)
    {
        $this->attributes[$name]->set($value);
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    /**
     * Returns a new Query based on current model and given method
     * @return Kabas\Drivers\Json\Query
     */
    public function __call($name, $arguments = [])
    {
        return $this->getDriver()->makeModelQuery($this, $name, $arguments);
    }

    /**
     * Returns a new empty Query based on given method
     * @return Kabas\Drivers\Json\Query
     */
    public static function __callStatic($name, $arguments = [])
    {
        return static::getDriver()->makeNewQuery($name, $arguments);
    }

    /**
     * Returns the driver for the current model from a static context
     * @return Kabas\Drivers\DriverInterface
     */
    public static function getDriver()
    {
        if(is_null(static::$driver)) {
            static::$driver = (new static())->getDriverInstance();
        }
        return static::$driver;
    }

    /**
     * Parses the model's structure file and stores values in cache
     * @return void
     */
    public function load()
    {
        $structure = File::loadJson($this->structure);
        static::$fields = $structure->fields ?? false;
    }

    /**
     * Returns the current model's object name
     * @return string
     */
    public function getObjectName() : string
    {
        return static::$object;
    }

    /**
     * Returns the current model's repository name
     * @return string
     */
    public function getRepository() : string
    {
        return static::$repository;
    }

    /**
     * Returns the current model's full path to its JSON structure file
     * @return string
     */
    public function getStructurePath() : string
    {
        $path = realpath(THEME_PATH . DS . 'structures' . DS . 'models' . DS . static::$structure);
        if(!$path) throw new \Kabas\Exceptions\FileNotFoundException($path);
        return $path;
    }

    /**
     * Returns the current model's fields container
     * @return object|false
     */
    public function getFields()
    {
        if(is_null(static::$fields)) $this->load();
        return static::$fields;
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
     * Initializes the object name
     * @return void
     */
    protected function constructObjectName()
    {
        if(strlen(static::$object)) return;
        static::$object = $this->generateObjectName();
    }

    /**
     * Guesses the model's repository name based on class name
     * @return string
     */
    protected function generateRepositoryName()
    {
        return static::$object . 's';
    }

    /**
     * Initializes the repository and attribute on this model
     * @return void
     */
    protected function constructRepositoryName()
    {
        static::$repository = static::$repository ?? $this->table ?? $this->generateRepositoryName();
    }

    /**
     * Gets the model's structure JSON filename
     * @return string
     */
    protected function generateStructureFile()
    {
        return static::$object . '.json';
    }

    /**
     * Initializes the structure attributes on this model
     * @return void
     */
    protected function constructStructureFileName()
    {
        static::$structure = static::$structure ?? $this->generateStructureFile();
    }

    /**
     * Makes the required field instance
     * @param string $name
     * @param mixed $value
     * @param object $structure
     * @return Kabas\Fields\[type]
     */
    protected function instanciateField($name, $value, \stdClass $structure)
    {
        try {
            $field = App::fields()->getClass($structure->type ?? 'text');
        } catch (\Kabas\Exceptions\TypeException $e) {
            $e->setFieldName($name, $this->getObjectName());
            $e->showAvailableTypes();
            echo $e->getMessage();
            return;
        }
        return new $field($name, $value, $structure);
    }
}
