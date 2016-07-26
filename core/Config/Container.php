<?php

namespace Kabas\Config;

use Kabas\App;
use Kabas\Utils\Text;

class Container
{
      public function __construct(Settings\Container $settings, Models\Container $models)
      {
            $this->loadAppConfig();
            $this->initDriver();
            $this->settings = $settings;
            $this->models = $models;
      }

      /**
       * Load the app config
       * @return void
       */
      protected function loadAppConfig()
      {
            $this->appConfig = require __DIR__ . '/AppConfig.php';
      }

      /**
       * Initialise the data driver based on
       * what's specified in the app config
       * @return void
       */
      protected function initDriver()
      {
            $driverName = 'Kabas\Drivers\\';
            $driverName .= Text::toNamespace($this->appConfig['driver']);
            $driver = App::getInstance()->make($driverName);
            App::setDriver($driver);
      }
}