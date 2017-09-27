<?php

namespace Kabas\Database;

use Kabas\Utils\Text;
use Kabas\Utils\Lang;
use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel
{
    use Concerns\HasFields;

    /**
    * Indicates if the model is translatable
    * @var bool
    */
    protected $translated = true;

    /**
     * Check if the model needs to be booted and if so, do it.
     *
     * @return void
     */
    protected function bootIfNotBooted()
    {
        if (! isset(static::$booted[static::class])) {
            static::$booted[static::class] = static::getModelInformation($this);

            $this->fireModelEvent('booting', false);

            static::boot();

            $this->fireModelEvent('booted', false);
        }
    }

    /**
     * Returns identity strings for given model
     * @param Kabas\Database\Model $model
     * @return array
     */
    protected static function getModelInformation($model)
    {
        return [
            'object' => ($object = static::getInstanceObjectName($model)),
            'repository' => static::getInstanceRepositoryName($model, $object),
            'structure' => static::getInstanceStructureFilename($model, $object)
        ];
    }

    /**
     * Returns the current model's object name
     * @return string
     */
    public function getObjectName() : string
    {
        return static::$booted[static::class]['object'];
    }

    /**
     * Returns the current model's repository name
     * @return string
     */
    public function getRepositoryName() : string
    {
        return static::$booted[static::class]['repository'];
    }

    /**
     * Returns the current model's strcuture filename
     * @return string
     */
    public function getStructureFilename() : string
    {
        return static::$booted[static::class]['structure'];
    }

    /**
     * Returns the current model's repository path for given locale
     * @param string $locale
     * @return string
     */
    public function getRepositoryPath($locale = null) : string
    {
        if(is_null($locale)) $locale = SHARED_DIR;
        return realpath(CONTENT_PATH) . DS . $locale . DS . $this->getRepositoryName();
    }

    /**
     * Returns the all the paths for the current model's content directories
     * @return array
     */
    public function getRepositories() : array
    {
        $paths = [SHARED_DIR => $this->getRepositoryPath()];
        if(!$this->isTranslatable()) return $paths;
        foreach (Lang::getAll() as $locale) {
            $paths[$locale->original] = $this->getRepositoryPath($locale->original);
        }
        return $paths; 
    }

    /**
     * Returns the current model's full path to its JSON structure file
     * @return string
     */
    public function getStructurePath() : string
    {
        $path = realpath(THEME_PATH . DS . 'structures' . DS . 'models' . DS . $this->getStructureFilename());
        if(!$path) throw new \Kabas\Exceptions\FileNotFoundException($path);
        return $path;
    }

    /**
     * Indicates if the model has translatable fields
     * @return bool
     */
    public function isTranslatable() : bool
    {
        return $this->translated;
    }

    /**
     * Retrieves the given model's object name
     * @param Kabas\Database\Model $model
     * @return string
     */
    protected static function getInstanceObjectName($model)
    {
        return  $model->getInitialProperty('object')
                ?? lcfirst(Text::removeNamespace(get_class($model)));
    }

    /**
     * Retrieves the given model's repository name
     * @param Kabas\Database\Model $model
     * @param string $objectName
     * @return string
     */
    protected static function getInstanceRepositoryName($model, $objectName)
    {
        return  $model->getInitialProperty('repository') ?? $objectName . 's';
    }

    /**
     * Retrieves the given model's structure filename
     * @param Kabas\Database\Model $model
     * @param string $objectName
     * @return string
     */
    protected static function getInstanceStructureFilename($model, $objectName)
    {
        return  $model->getInitialProperty('structure') ?? $objectName . '.json';
    }

    /**
     * Fill the model with an array of attributes.
     * @param  array  $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        parent::fill($attributes)->makeFieldsFromRawAttributes($attributes);
        return $this;
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
        $model->updateFieldsFromRawAttributes((array) $attributes);
        return $model;
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

    /**
     * Alias of getRepository
     * @return string
     */
    public function getTable()
    {
        return $this->getRepositoryName();
    }

    /**
     * Retrieves initial protected property if it exists
     * @param  string $property
     * @return string|null
     */
    public function getInitialProperty($property)
    {
        if(isset($this->$property)) return $this->$property;
        return null;
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
