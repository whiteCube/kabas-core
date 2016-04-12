<?php

namespace Kabas\Http\Responses;

use Kabas\View\View as ViewEngine;
use Kabas\Http\Response;

class View extends Response
{
      public function __construct($view, $data, $type)
      {
            $this->view = $view;
            $this->data = $data;
            $this->type = $type;
      }

      /**
       * Executes the response. Called automatically.
       * @return void
       */
      public function run()
      {
            $this->setHeaders();
            ViewEngine::make($this->view, $this->data, $this->type);
      }
}
