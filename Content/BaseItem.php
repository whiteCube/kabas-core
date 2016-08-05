<?php

namespace Kabas\Content;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Utils\Text;
use \Kabas\Fields\Item as Field;

class BaseItem
{
      public $id;

      public $template;

      public $options;

      public $fields;

      public $data;

      public $directory;

      protected $controller;

      protected $structure;

      public function __construct($data)
      {
            $this->id = isset($data->id) ? $data->id : false;
            $this->template = isset($data->template) ? $data->template : false;
            $this->fields = $this->loadFields(@$data->data);
            $this->options = isset($data->options) ? $data->options : new \stdClass();
            $this->controller = $this->findControllerClass();
            $this->setData($data);
      }

      public function __set($key, $value){
            if(isset($this->fields->$key)){
                  if(is_subclass_of($value, Field::class)) $this->fields->$key = $value;
                  else $this->fields->$key->set($value);
            }
            else{
                  if(!$this->data) $this->data = new \stdClass();
                  $this->data->$key = $value;
            }
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
            $this->controller = App::getInstance()->make($this->controller, [$this]);
      }

      protected function setData($data)
      {
            return null;
      }

      protected function loadFields($obj = null)
      {
            $this->loadStructure();
            $fields = null;

            if(is_object($obj)) $data = clone($obj);
            else $data = new \stdClass();
            if(isset($this->structure->fields)){
                  $fields = new \stdClass();
                  foreach ($this->structure->fields as $key => $field) {
                        $value = null;
                        if(isset($data->$key)){
                              $value = $data->$key;
                              unset($data->$key);
                        }
                        $fields->$key = App::fields()->make($key, $field, $value);
                  }
            }
            $this->set($data);
            return $fields;
      }

      protected function loadStructure()
      {
            if(is_null($this->structure)) $this->structure = File::loadJson($this->getStructureFile());
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
