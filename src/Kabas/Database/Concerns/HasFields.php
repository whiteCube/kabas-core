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
     * Set a given field on the model.
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setField($key, $value)
    {
        if(!isset($this->fields[$key])) return $this;
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
        return $this->fields[$key];
    }

    /**
     * Gets the model's structure file and stores values in cache
     * @return object|false
     */
    protected function loadRawFields()
    {
        $structure = File::loadJson($this->getStructurePath());
        return $structure->fields ?? false;
    }

    /**
     * Returns the current model's fields container
     * @return object|false
     */
    public function getRawFields()
    {
        if(!isset(static::$booted[static::class]['fields'])) {
            static::$booted[static::class]['fields'] = $this->loadRawFields();
        }
        return static::$booted[static::class]['fields'];
    }

    /**
     * Returns a raw field from static rawFields repository
     * @param string $key
     * @return object|false
     */
    public function getRawField($key)
    {
        if(!($fields = $this->getRawFields())) return false;
        return $fields->{$key} ?? false;
    }
    
    /**
     * Create all field instances for this model and fill with given attribute values
     * @param  array  $attributes
     * @return void
     */
    protected function makeFieldsFromRawAttributes($attributes)
    {
        foreach ($this->getRawFields() as $name => $structure) {
            $this->fields[$name] = App::fields()->make($name, $structure, $attributes[$name] ?? null);
        }
    }
    
    /**
     * Sets new values in existing field instances for this model
     * @param  array  $attributes
     * @return void
     */
    protected function updateFieldsFromRawAttributes($attributes)
    {
        foreach ($this->fields as $name => $field) {
            if(!isset($attributes[$name])) continue;
            $field->set(forward_static_call([get_class($field), 'format'], $attributes[$name]));
        }
    }
}