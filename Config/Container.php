<?php

namespace Kabas\Config;

class Container
{
      public function __construct()
      {
            $this->loadAppConfig();
            $this->initDriver();
            $this->settings = new Settings\Container();
            $this->pages = new Pages\Container();
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
            global $app;
            $driverName = '\Kabas\Drivers\\' . $this->appConfig['driver'];
            $app->driver = new $driverName;
      }
}
