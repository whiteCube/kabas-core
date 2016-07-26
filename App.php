<?php

namespace Kabas;

use WhiteCube\Bootstrap\FileLoader;
use \Illuminate\Container\Container;

class App extends Container
{
      /**
      * The current Kabas version
      * @var string
      */
      const VERSION = '0.1.1';

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
            self::$instance = $this;
            $this->setBaseConstants();
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
            // $this->singleton('session','Kabas\Session\SessionManager');
            $this->singleton('config', '\Kabas\Config\Container');
            $this->setThemePath();
            $this->singleton('fields', '\Kabas\FieldTypes\Container');
            $this->singleton('router', '\Kabas\Http\Router');
            $this->singleton('content', '\Kabas\Content\Container');
            $this->singleton('request', '\Kabas\Http\Request');
            $this->singleton('response', '\Kabas\Http\Response');
      }

      /**
       * Start up the application
       * @return void
       */
      public function boot()
      {
            $this->session = $this->make('Kabas\Session\SessionManager');
            $this->loadAliases();
            $this->loadTheme();
            $this->react();
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
            $loader = new FileLoader(THEME_PATH);
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
            $this->router->init();
            $this->page = $this->router->getCurrent()->page;
            $this->response->init($this->page);
            $this->session->write();
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

      /**
       * Get the name of the active theme
       * @return string
       */
      static function theme()
      {
            return self::config()->settings->site->theme;
      }

      protected function setBaseConstants()
      {
            define('DS', DIRECTORY_SEPARATOR);
            define('CORE_PATH', __dir__);
            define('BASE_PATH', preg_replace('/(\\' . DS . 'core)?/', '', CORE_PATH));
            define('CONTENT_PATH', BASE_PATH . DS . 'content');
            define('CONFIG_PATH', BASE_PATH . DS . 'config');
      }

      protected function setThemePath()
      {
            define('THEME_PATH', BASE_PATH . DS . 'themes' . DS . self::theme());
      }

}
