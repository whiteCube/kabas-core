<?php

namespace Kabas;

use WhiteCube\Bootstrap\FileLoader;

class App
{
      /**
      * The current Kabas version
      *
      * @var string
      */
      const VERSION = '0.0.3';

      /**
       * Activate debug mode
       *
       * @var boolean
       */
      const DEBUG = true;

      /**
       * The driver used to read data
       *
       * @var \Kabas\Drivers
       */
      public $driver;

      /**
       * The instance of the app
       *
       * @var Kabas
       */
      private static $instance;

      public function __construct()
      {
            self::$instance = $this;
      }

      /**
       * Start up the application
       *
       * @return void
       */
      public function boot()
      {
            $this->config = new Config\Container();
            $this->config->init();
            $this->request = new Http\Request();
            $this->router = new Http\Router();
            $this->response = new Http\Response();
      }

      /**
       * Check appConfig and load the specified aliases
       *
       * @return void
       */
      public function loadAliases()
      {
            foreach($this->config->appConfig['aliases'] as $alias => $class) {
                  class_alias($class, $alias);
            }
      }

      /**
       * Autoload the theme files
       *
       * @return void
       */
      public function loadTheme()
      {
            $loader = new FileLoader(__DIR__ . '/../themes/'. $this->config->settings->site->theme);
            $loader->autoload();
      }

      /**
       * Load the fields for each page
       * @return void
       */
      public function loadFields()
      {
            $this->config->pages->loadFields();
            $this->config->parts->loadFields();
      }

      /**
       * Returns app instance
       *
       * @return Kabas
       */
      static function getInstance()
      {
            return self::$instance;
      }

      /**
       * Sets the driver for the app;
       *
       * @param Driver $driver
       */
      static function setDriver($driver)
      {
            $app = self::getInstance();
            $app->driver = $driver;
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

      /**
       * Checks if app is in debug mode
       *
       * @return boolean
       */
      static function isDebug() {
            return static::DEBUG;
      }

}
