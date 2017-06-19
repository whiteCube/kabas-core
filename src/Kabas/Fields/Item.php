<?php

namespace Kabas\Fields;

use Kabas\Content\Container as Content;

class Item
{
    protected $type;

    protected $name;

    protected $output;

    protected $value;

    protected $default;

    protected $label;

    protected $description;

    protected $options;


    protected $multiple = false;


    public function __construct($name = null, $value = null, $structure = null)
    {
        $this->name = $name;
        $this->implement($structure);
        if(Content::isParsed()) $this->set($value);
        else $this->value = $value;
    }

    public function __toString()
    {
        return (string) $this->output;
    }

    /**
     * Formats a raw value in order and makes it usable for said field type
     * @param string $value
     * @return string
     */
    public static function format($value)
    {
        return trim($value);
    }

    /**
     * Retrieves the current field's type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Retrieves the current field's name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function get($key = null)
    {
        return $this->output;
    }

    /**
     * Retrieves the current field's raw value (as stored)
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Retrieves the current field's default value
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Retrieves the current field's textual label
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Retrieves the current field's textual description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Retrieves the current field's multiple-values state
     * @return boolean
     */
    public function isMultiple()
    {
        return $this->multiple;
    }

    /**
     * Defines field's value and updates its output
     * @param  mixed $value
     * @return void
     */
    public function set($value)
    {
        if($this->default && is_null($value)) $value = $this->default;
        if($this->multiple && !is_array($value) && !is_null($value)) $value = [$value];
        $this->value = $value;
        $this->output = $this->parse($value);

        if(!is_null($this->name) && !is_null($this->value)) {
            $this->check();
        }
    }

    /**
     * Defines multiple-values mode
     * @param  boolean $value
     * @return void
     */
    public function setMultiple($value = null)
    {
        $this->multiple = is_null($value) ? $this->multiple : $value;
    }

    /**
     * Sets option key if exists (used inside flexibleContent)
     * @param  string $option
     * @return void
     */
    public function setOption($option = null)
    {
        $this->option = is_string($option) ? $option : false;
    }

    /**
     * Generates the right form tag for this field
     * @param  array $attributes
     * @return string
     * @codeCoverageIgnore // This has yet to be implemented
     */
    public function input($attributes = [])
    {
        // TODO: make this rock.
        // $attributes
        // return '<input type="' . $this->type . '" value="' . $val . '" />';
    }

    /**
     * Runs a field validation on the given/current value
     * and throws an error if it returns false.
     * @param  string $field
     * @param  mixed $value
     * @return void
     */
    public function check($value = null)
    {
        if(!$this->validate($value)) {
            $error = 'Field "' . $this->name . '" of type "' . $this->type . '" has an incorrect value.';
            throw new \Kabas\Exceptions\TypeException($error);
        }
    }

    /**
     * Validates the given/current value
     * @param  string $field
     * @param  mixed $value
     * @return void
     */
    public function validate($value = null)
    {
        $value = is_null($value) ? $this->value : $value;
        return $this->condition($value);
    }

    /**
     * Builds field's attributes based on user-defined structure
     * @param  object $structure
     * @return void
     */
    protected function implement($structure)
    {
        $this->default = @$structure->default;
        $this->label = isset($structure->label) ? trim($structure->label) : ucfirst($this->type);
        $this->description = isset($structure->description) ? $structure->description : null;
        $this->setOption(@$structure->option);
    }

    /**
     * Makes an output value from given raw value
     * @param  mixed $value
     * @return mixed
     */
    protected function parse($value)
    {
        return $value;
    }
}
