<?php

namespace Kabas\Http;

class RouteNotFound extends Route
{

      public function __construct($page = null)
      {
            $this->string = false;
            $this->regex = false;
            $this->page = '404';
      }

      public function matches($route, $lang)
      {
            return false;
      }

}
