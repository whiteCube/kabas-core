<?php

namespace Kabas\Controller;

use Kabas\View\View;
use Kabas\App;

class BaseController
{
      protected $view;
      protected $config;

      public function __construct($view)
      {
            $this->defaultTemplateName = $view->template;
            $this->type = $this->getType();
            $this->viewID = $view->id;
            $this->data = isset($view->data) ? $view->data : new \stdClass;
            $this->options = isset($view->options) ? $view->options : null;
            $this->meta = isset($view->meta) ? $view->meta : null;
            $params = App::router()->getParams();
            call_user_func_array([$this, 'setup'], $params);
            $this->checkLinkedFiles();
            $this->render($this->constructViewData());
      }


      public function __call($method, $args)
      {

      }

      /**
       * Get the type of this template
       * @return string
       */
      public function getType()
      {
            return strtolower(explode('\\', get_class($this))[2]);
      }

      /**
       * Get the data from $this and format it into an object
       * that can be passed to the view.
       * @return stdClass
       */
      protected function constructViewData()
      {
            App::config()->pages->loadCurrentPageFields();
            $this->checkValues($this->type);

            $data = $this->data;
            $data->options = $this->options;
            $data->meta = $this->meta;

            return $data;
      }

      /**
      * Render the current view onto the page.
      * @param  stdClass $data
      * @return void
      */
      protected function render($data)
      {
            View::make($this->view, $data, $this->type);
      }

      /**
       * Check if the theme provided a custom template filename
       * @return void
       */
      protected function checkLinkedFiles()
      {
            if(!$this->view) $this->view = $this->guessViewFile();
      }

      /**
       * Complete data with default values and then check
       * if values correspond to types.
       * @param  string $type
       * @return void
       */
      protected function checkValues($type)
      {
            if(isset(App::config()->$type->items[$this->viewID])){
                  foreach(App::config()->$type->items[$this->viewID]->fields as $fieldName => $fieldDetails) {
                        $type = $fieldDetails->type;

                        if(!isset($this->data->$fieldName)) {
                              $this->data->$fieldName = $fieldDetails->defaultValue;
                        }

                        try { App::config()->fieldTypes->exists($type); }
                        catch (\Kabas\Exceptions\TypeException $e) {
                              $e->setFieldName($fieldName, $this->viewID);
                              $e->showAvailableTypes();
                              echo $e->getMessage();
                              die();
                        }

                        $class = App::config()->fieldTypes->getClass($type)->class;
                        if(isset($fieldDetails->allowsMultipleValues) && $type === 'select') {
                              $this->data->$fieldName = App::getInstance()->make($class, [$fieldName, $this->data->$fieldName, $fieldDetails->allowsMultipleValues]);
                        } else {
                              $this->data->$fieldName = App::getInstance()->make($class, [$fieldName, $this->data->$fieldName]);
                        }

                  }
            }
      }

      /**
       * Get the default template filename
       * @return string
       */
      protected function guessViewFile()
      {
            return $this->defaultTemplateName;
      }

}
