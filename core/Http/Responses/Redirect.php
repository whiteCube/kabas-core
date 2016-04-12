<?php

namespace Kabas\Http\Responses;

use Kabas\Utils\Url;
use Kabas\Http\Response;

class Redirect extends Response
{
      public function __construct($pageID, $params = [], $lang = null)
      {
            $this->target = $pageID;
            $this->params = $params;
            $this->lang = $lang;
      }

      /**
       * Executes the response. Called automatically.
       * @return void
       */
      public function run()
      {
            $this->setHeaders();
            header('Location: ' . Url::to($this->target, $this->params, $this->lang));
            die();
      }
}
