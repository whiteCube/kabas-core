<?php

namespace Kabas\Controller;

use Kabas\View\View;

class BaseController
{
      protected $view;
      protected $config;

      public function __construct($template, $data, $options = null)
      {
            $this->defaultTemplateName = $template;
            $this->checkLinkedFiles();

            $this->data = $data;
            $this->options = $options;
            $this->setup();

            $this->render($this->constructViewData());
      }

      /**
       * In this function you get the chance to process the data inside
       * $this->data and $this->options before it is passed on to the
       * view. Please re-declare it in your menu's controller.
       * @return void
       */
      public function setup()
      {
      }

      /**
       * Get the data from $this and format it into an object
       * that can be passed to the view.
       * @return stdClass
       */
      protected function constructViewData()
      {
            $data = $this->data;
            $data->options = $this->options;

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
