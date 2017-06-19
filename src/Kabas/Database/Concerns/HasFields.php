<?php

namespace Kabas\Database\Concerns;

use Kabas\App;
use Kabas\Utils\File;

trait HasFields
{
    /**
    * The instanciateded fields for this model
    * @var array
    */
    protected $fields = [];

    /**
    * The current model's defined fields
    * @var object
    */
    static protected $rawFields;

    /**
     * Set a given field on the model.
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setField($key, $value)
    {
        //  TODO : apply set mutator
        $this->fields[$key]->set($value);
        return $this;
    }

    /**
     * Get a field from the model.
     * @param  string  $key
     * @return Kabas\Fields\[type]|null
     */
    public function getField($key)
    {
        if(!isset($this->fields[$key])) return;
        //  TODO : apply get mutator
        return $this->fields[$key];
    }

    /**
     * Parses the model's structure file and stores values in cache
     * @return void
     */
    protected function loadFields()
    {
        $structure = File::loadJson($this->getStructurePath());
        static::$rawFields = $structure->fields ?? false;
    }

    /**
     * Returns the current model's fields container
     * @return object|false
     */
    public function getFields()
    {
        if(is_null(static::$rawFields)) $this->loadFields();
        return static::$rawFields;
    }
    
    /**
     * Create all field instances for this model and fill with given attribute values
     * @param  array  $attributes
     * @param  string|null  $connection
     * @return static
     */
    protected function makeFieldsFromRawAttribbutes($attributes)
    {
        foreach ($this->getFields() as $name => $structure) {
            $this->fields[$name] = $this->createField($name, $attributes[$name] ?? null, $structure);
        }
    }

    /**
     * Makes the required field instance
     * @param string $name
     * @param mixed $value
     * @param object $structure
     * @return Kabas\Fields\[type]
     */
    public function createField($name, $value, \stdClass $structure)
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