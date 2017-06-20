<?php

namespace Kabas\Fields\Types;

use \Kabas\App;
use \Kabas\Fields\Groupable;

class Partial extends Groupable
{

    protected $reference = true;

    /**
     * includes referenced partial's view with its data
     * @param  string $option
     * @return void
     */
    public function render()
    {
        if($this->reference){
            $this->reference->set($this->output);
            $this->reference->make();
        }
        return $this;
    }

    /**
     * Sets reference & other field data
     * @return void
     */
    protected function implement($structure)
    {
        parent::implement($structure);
        $this->reference = @$structure->option;
    }

    /**
     * Loads partial, defines field's value and updates its output
     * @param  mixed $value
     * @return void
     */
    public function set($value)
    {
        if(is_string($this->reference)) $this->setOptions($this->reference);
        parent::set($value);
    }

    /**
     * makes options from user defined list
     * @param  string $part
     * @return array
     */
    protected function makeOptions($part)
    {
        $a = [];
        if($fields = $this->getPartFields($part)){
            foreach($fields as $name => $field){
                $a[$name] = $field;
            }
        }
        return $a;
    }

    /**
     * Gets requested partial's fields
     * @param  string $part
     * @return array
     */
    protected function getPartFields($part)
    {
        $part = App::content()->partials->load($part);
        if($part){
            $this->reference = $part;
            if(is_object($part->fields)) return $part->fields;
            return [];
        }
        $this->reference = false;
        return false;
    }

    /**
     * Makes an array of defined fields
     * @param  object $value
     * @return array
     */
    protected function parse($value)
    {
        $a = [];
        if($this->options) {
            foreach ($this->options as $key => $field) {
                $val = isset($value->$key) ? $value->$key : null;
                $a[$key] = clone $field;
                $a[$key]->set($val);
            }
        }
        return $a;
    }
}
