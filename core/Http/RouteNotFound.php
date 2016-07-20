<?php

namespace Kabas\Http;

class RouteNotFound extends Route
{

      public function __construct($page = null)
      {
            $this->string = false;
            $this->regex = false;
            $this->pageID = '404';
      }

      public function matches($route)
      {
            return false;
      }

}
