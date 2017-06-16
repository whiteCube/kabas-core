<?php

namespace Kabas\Model;

use Kabas\App;
use Kabas\Utils\Text;
use Kabas\Utils\File;

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
    * The current instance's raw values
    * @var array
    */
    protected $original = [];

    /**
    * The current instance's field values
    * @var array
    */
    protected $attributes = [];

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
    * The current model's defined fields
    * @var object
    */
    static protected $fields;

    /**
    * The current model's driver instance
    * @var object
    */
    private $driver;

    public function __construct(array $attributes = [])
    {
        $this->object = $this->object ?? $this->generateObjectName();
        $this->repository = $this->repository ?? $this->generateRepositoryName();
        $this->structure = $this->generateStructurePath($this->structure);
        $this->driver = $this->getDriverInstance();
        $this->inject($attributes);
    }

    public function __set($name, $value)
    {
        $this->attributes[$name]->set($value);
    }

    public function __get($name)
    {
        return $this->attributes[$name];
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

    /**
     * Fills attribute fields and original values
     * @param array $attributes
     * @return void
     */
    protected function inject(array $attributes)
    {
        foreach ($this->getFields() as $key => $field) {
            $this->original[$key] = $attributes[$key] ?? null;
            $this->attributes[$key] = $this->instanciateField($key, $this->original[$key], $field);
        }
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
            echo $e->getMessage();
            return;
        }
        return new $field($name, $value, $structure);
    }
}
