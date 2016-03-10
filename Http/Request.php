<?php

namespace Kabas\Http;

class Request
{
      public function __construct()
      {
            $this->method = $_SERVER['REQUEST_METHOD'];
      }

      /**
       * Check if request is POST
       * @return boolean
       */
      public function isPost()
      {
            if($this->method === 'POST') return true;
            return false;
      }

      /**
       * Check if request is GET
       * @return boolean
       */
      public function isGet()
      {
            if($this->method === 'GET') return true;
            return false;
      }
}
