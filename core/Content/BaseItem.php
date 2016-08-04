<?php

namespace Kabas\Content;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Utils\Text;

class BaseItem
{
      public $id;

      public $template;

      public $options;

      public $fields;

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

      public function build($data = null)
      {
            $this->mergeData($data);
      }

      public function set($data)
      {
            foreach ($data as $key => $value) {
                  if(is_object($value) && is_subclass_of($value, \Kabas\Fields\Item::class)){
                        $this->data->$key = $value;
                  }
                  else {
                        $this->data->$key->set($value);
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

      protected function loadFields($data = null)
      {
            $this->loadStructure();
            $fields = new \stdClass();
            if(!is_object($data)) $data = new \stdClass();
            if(!isset($this->structure->fields)) return null;
            foreach ($this->structure->fields as $key => $field) {
                  $value = isset($data->$key) ? $data->key : null;
                  $fields->$key = App::fields()->make($key, $field, $value);
            }
            return $fields;
      }

      protected function mergeData($data)
      {
            if($data){
                  foreach ($data as $key => $value) {
                        $this->data->$key = $value;
                  }
            }
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
