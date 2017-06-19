<?php

namespace Kabas\Database;

use Kabas\Utils\Text;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel
{
    use Concerns\HasFields;

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
     * Create a new model instance that is existing.
     * @param  array  $attributes
     * @param  string|null  $connection
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        $model = parent::newFromBuilder($attributes, $connection);
        $model->makeFieldsFromRawAttribbutes((array) $attributes);
        return $model;
    }

    /**
     * Dynamically retrieve attributes on the model.
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getField($key) ?? $this->getAttribute($key);
    }
}
