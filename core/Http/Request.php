<?php

namespace Kabas\Http;

class Request
{
      /**
       * The request method.
       * @var string
       */
      public $method;

      public function __construct()
      {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->constructData();
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

      protected function constructData()
      {
            $this->get = (object) $_GET;
            $this->post = (object) $_POST;
      }
}
