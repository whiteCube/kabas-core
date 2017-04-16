<?php

namespace Kabas;

use \Illuminate\Container\Container;

class App extends Container
{
      /**
      * The current Kabas version
      * @var string
      */
      const VERSION = '0.1.4';

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

      public function __construct($public_path)
      {
            self::$instance = $this;
            $this->registerPaths($public_path);
            $this->registerBindings();
      }

      public static function __callStatic($name, $arguments)
      {
            if(!method_exists(self::$instance, $name)) {
                  return self::$instance->$name;
            }
      }

      /**
       * Start up the application
       * @return void
       */
      public function boot()
      {
            $this->loadAliases();
            $this->themes->loadCurrent();
            $this->router->load()->setCurrent();
            $this->content->parse();
            $this->page = $this->router->getCurrent()->page;
            $this->response->init($this->page);
            $this->session->write();
      }

      /**
       * Load config/app's specified aliases
       * @return void
       */
      public function loadAliases()
      {
            foreach($this->config->get('app.aliases') as $alias => $class) {
                  class_alias($class, $alias);
            }
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
       * Defines path constants
       * @return void
       */
      protected function registerPaths($public_path)
      {
            define('DS', DIRECTORY_SEPARATOR);
            define('CORE_PATH', __DIR__);
            define('PUBLIC_PATH', $public_path);
            define('ROOT_PATH', realpath(PUBLIC_PATH . DS .'..'));
            define('CONTENT_PATH', ROOT_PATH . DS . 'content');
            define('CONFIG_PATH', ROOT_PATH . DS . 'config');
            define('THEMES_PATH', ROOT_PATH . DS . 'themes');
      }

      /**
       * Defines app singletons
       * @return void
       */
      protected function registerBindings()
      {
            $this->singleton('session', '\\Kabas\\Session\\SessionManager');
            $this->singleton('config', '\\Kabas\\Config\\Container');
            $this->singleton('fields', '\\Kabas\\Fields\\Container');
            $this->singleton('router', '\\Kabas\\Http\\Router');
            $this->singleton('content', '\\Kabas\\Content\\Container');
            $this->singleton('request', '\\Kabas\\Http\\Request');
            $this->singleton('response', '\\Kabas\\Http\\Response');
            $this->singleton('themes', '\\Kabas\\Themes\\Container');
      }

}
