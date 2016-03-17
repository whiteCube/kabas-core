<?php

namespace Kabas\Config;

use \Kabas\App;

class Container
{
      public function __construct()
      {



      }

      /**
       * Load the app config
       *
       * @return void
       */
      protected function loadAppConfig()
      {
            $this->appConfig = require __DIR__ . '/AppConfig.php';
      }

      /**
       * Initialise the data driver based on
       * what's specified in the app config
       *
       * @return void
       */
      protected function initDriver()
      {
            $driverName = '\Kabas\Drivers\\' . $this->appConfig['driver'];
            App::setDriver(new $driverName);
      }

      public function init()
      {
            $this->loadAppConfig();
            $this->initDriver();
            $this->settings = new Settings\Container();
            $this->pages = new Pages\Container();
            $this->parts = new Parts\Container();
            $this->menus = new Menus\Container();
      }
}
