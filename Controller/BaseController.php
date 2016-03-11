<?php

namespace Kabas\Controller;

use Kabas\View\View;

class BaseController
{
      protected $view;
      protected $config;

      public function __construct($template, $data)
      {
            $this->defaultTemplateName = $template;
            $this->checkLinkedFiles();
            $this->render($data);
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
