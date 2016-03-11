<?php

namespace Kabas;

class Kabas
{
      /**
      * The current Kabas version
      *
      * @var string
      */
      const VERSION = '0.0.1';

      /**
       * The driver used to read data
       *
       * @var \Kabas\Drivers
       */
      public $driver;

      /**
       * Start up the application
       *
       * @return void
       */
      public function boot()
      {
            $this->config = new Config\Container();
            $this->request = new Http\Request();
            $this->router = new Http\Router();
            $this->response = new Http\Response();
      }

      /**
       * Once we're all set, take care of the request
       * and send something back!
       *
       * @return void
       */
      public function react()
      {
            $this->page = $this->router->handle();
            $this->response->send($this->page);
      }

      /**
      * Get the version number of this Kabas website.
      *
      * @return string
      */
      public function version()
      {
            return static::VERSION;
      }

}
