<?php

namespace Kabas\Config;

use \Kabas\App;

class Container
{
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

      public function initSettings()
      {
            $this->loadAppConfig();
            $this->initDriver();
            $this->settings = new Settings\Container();
      }

      public function initParts()
      {
            $this->pages = new Pages\Container();
            $this->parts = new Parts\Container();
            $this->menus = new Menus\Container();
      }
}
