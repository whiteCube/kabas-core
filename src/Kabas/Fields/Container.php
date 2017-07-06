<?php

namespace Kabas\Fields;

use \Kabas\App;
use \Kabas\Utils\Text;

class Container
{
    /**
     * All the supported field types
     * @var array
     */
    protected $supported = [];

    const DEFAULT = 'text';
    const KEY = 'number';

    public function __construct()
    {
        foreach(scandir( __DIR__ . DS . 'Types' ) as $file) {
            if($file !== '.' && $file !== '..') {
                $this->loadFieldType($file);
            }
        }
    }

    /**
     * Creates a field based on its structure's type
     * @param  string $name
     * @param  object $structure
     * @param  mixed $value
     * @return object
     */
    public function make($name, $structure, $value = null)
    {
        // If the developper did not provide a field type, we'll
        // assume it is a regular text field.
        $type = $this->getClass($structure->type ?? static::DEFAULT);
        return new $type($name, $value, $structure);
    }

    /**
     * Get a FieldType class if it exists
     * @param  string $type
     * @return string
     */
    public function getClass($type)
    {
        if(isset($this->supported[$type])) return $this->supported[$type]->class;
        else{
            $error = 'Type "' . $type . '" is not a supported field type.';
            throw new \Kabas\Exceptions\TypeException($error);
        }
    }

    /**
     * Load a field type based on its filename
     * @param  string $file
     * @return void
     */
    protected function loadFieldType($file)
    {
        $type = $this->getFieldTypeObject($file);
        $this->supported[$type->name] = $type;
    }

    /**
     * Get FieldType info
     * @param  string $file
     * @return object
     */
    protected function getFieldTypeObject($file)
    {
        $type = new \stdClass();
        $type->name = strtolower(basename($file, '.php'));
        $type->class = '\\Kabas\\Fields\\Types\\' . Text::toNamespace($type->name);
        return $type;
    }

    public function getSupported()
    {
        return $this->supported;
    }

}
