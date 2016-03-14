<?php

namespace Kabas\Config;

use \Kabas\Kabas;

class Container
{
      public function __construct()
      {
            $this->loadAppConfig();
            $this->initDriver();
            $this->settings = new Settings\Container();
            $this->pages = new Pages\Container();
            $this->parts = new Parts\Container();
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
            Kabas::setDriver(new $driverName);
      }
}
