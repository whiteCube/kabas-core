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
            $this->checkLinkedFiles();
            $this->viewID = $view->id;

            $this->data = $view->data;
            $this->options = isset($view->options) ? $view->options : null;
            $this->meta = isset($view->meta) ? $view->meta : null;
            $this->setup();

            $this->render($this->constructViewData());
      }

      /**
       * In this function you get the chance to process the data inside
       * $this->data and $this->options before it is passed on to the
       * view. Please re-declare it in your template's controller.
       * @return void
       */
      protected function setup()
      {
      }

      /**
       * Get the data from $this and format it into an object
       * that can be passed to the view.
       * @return stdClass
       */
      protected function constructViewData()
      {
            $this->checkValues('pages');
            $this->checkValues('parts');

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
            View::make($this->view, $data);
      }

      /**
       * Check if the theme provided a custom template or config filename
       * @return void
       */
      protected function checkLinkedFiles()
      {
            if(!$this->view) $this->view = $this->guessViewFile();
            if(!$this->config) $this->config = $this->guessConfigFile();
      }

      protected function checkValues($type)
      {
            if(isset(App::config()->$type->items[$this->viewID])){
                  foreach(App::config()->$type->items[$this->viewID]->fields as $field => $data) {
                        if(!isset($this->data->$field)) {
                              $this->data->$field = $data->defaultValue;
                        }
                        try {
                              App::config()->fieldTypes->exists($data->type);
                              try {
                                    App::config()->fieldTypes->types[$data->type]->check($field, $this->data->$field);
                              } catch (\Kabas\Exceptions\TypeException $e) {
                                    echo $e->getMessage();
                              }
                        } catch (\Kabas\Exceptions\TypeException $e) {
                              echo $e->getMessage();
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

      /**
       * Get the default config filename
       * @return string
       */
      protected function guessConfigFile()
      {

      }

}
