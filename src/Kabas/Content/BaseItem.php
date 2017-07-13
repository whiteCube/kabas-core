<?php

namespace Kabas\Content;

use Kabas\App;
use Kabas\Utils\File;
use Kabas\Utils\Text;
use Kabas\Fields\Item as Field;
use Kabas\Exceptions\FileNotFoundException;

class BaseItem
{
    public $id;

    public $_title;

    public $template;

    public $options;

    public $fields;

    public $data;

    public $directory;

    protected $controller;

    protected $structure;

    public function __construct(\stdClass $data)
    {
        $this->id = isset($data->id) ? $data->id : false;
        $this->_title = isset($data->title) ? $data->title : 'Untitled';
        $this->template = isset($data->template) ? $data->template : false;
        $this->fields = $this->loadFields(@$data->data);
        $this->options = isset($data->options) ? $data->options : new \stdClass();
        $this->controller = $this->findControllerClass();
        $this->setData($data);
    }

    public function __set($key, $value)
    {
        if(isset($this->fields->$key)){
            if(is_subclass_of($value, Field::class)) $this->fields->$key = $value;
            else $this->fields->$key->set($value);
        }
        else{
            if(!$this->data) $this->data = new \stdClass();
            $this->data->$key = $value;
        }
    }

    public function __get($key)
    {
        if(isset($this->fields->$key)) return $this->fields->$key;
        elseif(isset($this->data->$key)) return $this->data->$key;
        return null;
    }

    public function __call($name, $args)
    {
        if(!is_object($this->controller)) return false;
        return call_user_func_array([$this->controller, $name], $args);
    }

    public function set($data)
    {
        if(is_array($data) || is_object($data)){
            foreach ($data as $key => $value) {
                $this->__set($key, $value);
            }
        }
    }

    public function make()
    {
        $controller = !is_string($this->controller) ? get_class($this->controller) : $this->controller;
        $this->controller = new $controller($this);
    }

    public function parse()
    {
        if($this->fields){
            foreach ($this->fields as $field) {
                $field->set($field->getValue());
            }
        }
    }

    protected function setData($data)
    {
        return null;
    }

    protected function loadFields($obj = null)
    {
        $this->loadStructure();
        $fields = null;
        $data = $this->getFieldObject($obj);
        if(isset($this->structure->fields)) $fields = $this->getItemFields($data, $this->structure->fields);
        $this->set($data);
        return $fields;
    }

    protected function getFieldObject($o)
    {
        if(is_object($o)) return clone($o);
        return new \stdClass();
    }

    protected function getItemFields(&$data, $structure)
    {
        $fields = new \stdClass();
        foreach ($structure as $key => $field) {
            $value = null;
            if(isset($data->$key)){
                $value = $data->$key;
                unset($data->$key);
            }
            $fields->$key = App::fields()->make($key, $field, $value);
        }
        return $fields;
    }

    protected function loadStructure()
    {
        if(!is_null($this->structure)) return;
        try {
            $this->structure = File::loadJson($this->getStructureFile());
        } catch (FileNotFoundException $e) {
            $this->structure = null;
        }
    }

    protected function getStructureFile()
    {
        $path = THEME_PATH . DS . 'structures' . DS;
        $path .= $this->directory . DS;
        $path .= $this->template . '.json';
        return $path;
    }

    protected function findControllerClass()
    {
        $class = $this->getTemplateNamespace();
        if(class_exists($class)) return $class;
        return false;
    }

    protected function getTemplateNamespace()
    {
        return Text::toNamespace($this->template);
    }
}
