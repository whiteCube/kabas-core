<?php

namespace Kabas\Config;

use \Kabas\App;
use \Kabas\Utils\Benchmark;

class Container
{
      public function __construct(Settings\Container $settings, FieldTypes\Container $fieldTypes, Models\Container $models)
      {
            $this->loadAppConfig();
            $this->initDriver();
            $this->settings = $settings;
            $this->fieldTypes = $fieldTypes;
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
            $driverName = '\Kabas\Drivers\\' . $this->appConfig['driver'];
            $driver = App::getInstance()->make($driverName);
            App::setDriver($driver);
      }

      public function initParts(Pages\Container $pages, Parts\Container $parts, Menus\Container $menus)
      {
            $this->pages = $pages;
            $this->parts = $parts;
            $this->menus = $menus;
      }

}
