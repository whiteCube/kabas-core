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
            $this->singleton('config', function() {
                  $settings = $this->make('Kabas\Config\Settings\Container');
                  $fieldTypes = $this->make('Kabas\Config\FieldTypes\Container');
                  return new \Kabas\Config\Container($settings, $fieldTypes);
            });
            $this->singleton('request', function() {
                  return new \Kabas\Http\Request();
            });
            $this->singleton('router', function() {
                  return new \Kabas\Http\Router();
            });
            $this->singleton('response', function() {
                  return new \Kabas\Http\Response();
            });
            $this->bind('PageItem', function($app, $args) {
                  return new \Kabas\Config\Pages\Item($args[0]);
            });
            $this->bind('PartItem', function($app, $args) {
                  return new \Kabas\Config\Parts\Item($args[0]);
            });
            $this->bind('MenuItem', function($app, $args) {
                  return new \Kabas\Config\Menus\Item($args[0]);
            });
            $this->bind('SelectableOption', function($app, $args) {
                  return new \Kabas\Config\FieldTypes\Option($args[0]);
            });
            $this->bind('ImageItem', function($app, $args) {
                  return new \Kabas\Objects\Image\Item($args[0]);
            });
      }

      /**
       * Start up the application
       * @return void
       */
      public function boot()
      {
            $pages = $this->make('Kabas\Config\Pages\Container');
            $parts = $this->make('Kabas\Config\Parts\Container');
            $menus = $this->make('Kabas\Config\Menus\Container');
            $this->config->initParts($pages, $parts, $menus);
            $this->setConstant();
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
      static function isDebug() {
            return static::DEBUG;
      }

      protected function setConstant(){
            define('DS', DIRECTORY_SEPARATOR);
            define('CORE_PATH', __dir__);
            define('BASE_PATH', preg_replace('/(\\' . DIRECTORY_SEPARATOR . 'core)?/', '', CORE_PATH));
            define('THEME_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $this->config->settings->site->theme);
      }

}
