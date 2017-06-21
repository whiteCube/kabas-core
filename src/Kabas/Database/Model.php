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
        static::constructObjectName();
        static::constructRepositoryName();
        static::constructStructureFileName();
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
     * Initializes the object name
     * @return void
     */
    protected static function constructObjectName()
    {
        if(strlen(static::$object)) return;
        static::$object = lcfirst(Text::removeNamespace(static::class));
    }

    /**
     * Initializes the repository and attribute on this model
     * @return void
     */
    protected static function constructRepositoryName()
    {
        static::$repository = static::$repository ?? static::$object . 's';
    }

    /**
     * Initializes the structure attributes on this model
     * @return void
     */
    protected static function constructStructureFileName()
    {
        static::$structure = static::$structure ?? static::$object . '.json';
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

    /**
     * Set a given attribute on the model.
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        return parent::setAttribute($key, $value)
                ->setField($key, $this->getAttributeValue($key));
    }
}
