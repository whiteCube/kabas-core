<?php

namespace Kabas\Content;

use \Kabas\App;
use \Kabas\Utils\File;
use \Kabas\Utils\Text;

class BaseItem
{
      public $id;

      public $template;

      public $data;

      public $options;

      public $fields;

      public $directory;

      protected $controller;

      protected $structure;

      public function __construct($data)
      {
            // TODO : load fields structure immediately, and update it later if needed (in build())
            $this->id = isset($data->id) ? $data->id : false;
            $this->template = isset($data->template) ? $data->template : false;
            $this->data = isset($data->data) ? $data->data : new \stdClass();
            $this->options = isset($data->options) ? $data->options : new \stdClass();
            $this->controller = $this->findControllerClass();
            $this->setData($data);
      }

      public function build($data = null)
      {
            $this->mergeData($data);
            $this->loadFields();
      }

      public function loadFields()
      {
            $structure = $this->getStructure();
            $this->fields = isset($structure->fields) ? $structure->fields : new \stdClass();
            $this->updateData();
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

      protected function mergeData($data)
      {
            if($data){
                  foreach ($data as $key => $value) {
                        $this->data->$key = $value;
                  }
            }
      }

      protected function getStructure()
      {
            if(is_null($this->structure)) $this->structure = File::loadJson($this->getStructureFile());
            return $this->structure;
      }

      protected function getStructureFile()
      {
            $path = THEME_PATH . DS . 'structures' . DS;
            $path .= $this->directory . DS;
            $path .= $this->template . '.json';
            return $path;
      }

      protected function updateData()
      {
            foreach ($this->fields as $key => $field) {
                  if(!isset($this->data->$key)) $this->data->$key = null;
                  try { 
                        $this->makeFieldData($key, $field);
                  }
                  catch (\Kabas\Exceptions\TypeException $e) {
                        $e->setFieldName($key, $this->id);
                        // TODO : shouldn't showAvailableTypes be called systematically in getMessage ?
                        $e->showAvailableTypes();
                        echo $e->getMessage();
                        die();
                  }
            }
      }

      protected function makeFieldData($key, $field)
      {
            $class = App::fields()->getClass(isset($field->type) ? $field->type : 'text');
            $this->data->$key = App::getInstance()->make($class, [$key, $this->data->$key, $field]);
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
