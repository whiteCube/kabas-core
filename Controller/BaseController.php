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
            $this->setup($options);
            $this->render($data);
      }

      public function setup($options)
      {

      }

      protected function render($data)
      {
            View::make($this->view, $data);
      }

      protected function checkLinkedFiles()
      {
            if(!$this->view) $this->view = $this->guessViewFile();
            if(!$this->config) $this->config = $this->guessConfigFile();
      }

      protected function guessViewFile()
      {
            return $this->defaultTemplateName;
      }

      protected function guessConfigFile()
      {

      }

}
