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
            $this->viewID = $view->id;
            $this->data = $view->data;
            $this->options = isset($view->options) ? $view->options : null;
            $this->meta = isset($view->meta) ? $view->meta : null;

            $this->setup();
            $this->checkLinkedFiles();
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

                        $class = get_class(App::config()->fieldTypes->types[$type]);
                        $this->data->$fieldName = new $class($fieldName, $this->data->$fieldName);

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
