<?php

namespace Kabas;

use WhiteCube\Bootstrap\FileLoader;
use \Kabas\Utils\Benchmark;
use \Illuminate\Container\Container;

class App extends Container
{
      /**
      * The current Kabas version
      * @var string
      */
      const VERSION = '0.0.3';

      /**
       * Activate debug mode
       * @var boolean
       */
      const DEBUG = true;

      /**
       * The driver used to read data
       * @var \Kabas\Drivers
       */
      public $driver;

      /**
       * The instance of the app
       * @var Kabas
       */
      protected static $instance;

      public function __construct()
      {
            Benchmark::start('start to finish');
            self::$instance = $this;

            $this->registerBindings();

      }

      public static function __callStatic($name, $arguments)
      {
            if(!method_exists(self::$instance, $name)) {
                  return self::$instance->$name;
            }
      }

      public function registerBindings()
      {
            $this->singleton('config', '\Kabas\Config\Container');
            $this->singleton('request', '\Kabas\Http\Request');
            $this->singleton('router', '\Kabas\Http\Router');
            $this->singleton('response', '\Kabas\Http\Response');
      }

      /**
       * Start up the application
       * @return void
       */
      public function boot()
      {
            $this->setBaseConstants();
            $pages = $this->make('Kabas\Config\Pages\Container');
            $parts = $this->make('Kabas\Config\Parts\Container');
            $menus = $this->make('Kabas\Config\Menus\Container');
            $this->config->initParts($pages, $parts, $menus);
            $this->setConstants();
      }

      /**
       * Check appConfig and load the specified aliases
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
       * @return void
       */
      public function loadTheme()
      {
            $loader = new FileLoader(__DIR__ . '/../themes/'. $this->config->settings->site->theme);
            $loader->autoload();
      }

      /**
       * Returns app instance
       * @return Kabas
       */
      static function getInstance()
      {
            return self::$instance;
      }

      /**
       * Sets the driver for the app;
       * @param Driver $driver
       */
      static function setDriver($driver)
      {
            self::$instance->driver = $driver;
      }

      /**
       * Once we're all set, take care of the request
       * and send something back!
       * @return void
       */
      public function react()
      {
            $this->page = $this->router->getCurrentPageID();
            $this->response->send($this->page);
      }

      /**
      * Get the version number of this Kabas website.
      * @return string
      */
      public function version()
      {
            return static::VERSION;
      }

      /**
       * Checks if app is in debug mode
       * @return boolean
       */
      static function isDebug()
      {
            return static::DEBUG;
      }

      protected function setBaseConstants()
      {
            define('DS', DIRECTORY_SEPARATOR);
      }

      protected function setConstants()
      {
            define('CORE_PATH', __dir__);
            define('BASE_PATH', preg_replace('/(\\' . DIRECTORY_SEPARATOR . 'core)?/', '', CORE_PATH));
            define('THEME_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $this->config->settings->site->theme);
      }

}
