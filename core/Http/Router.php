<?php

namespace Kabas\Http;

class Router
{
      public function __construct()
      {
            global $app;
            $this->route = $_SERVER['REDIRECT_URL'];
      }

}
