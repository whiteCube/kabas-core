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
