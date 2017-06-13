<?php

namespace Kabas;

use Kabas\Http\Router;
use Kabas\Http\UrlWorker;
use \Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

class App extends Container
{
    /**
    * The current Kabas version
    * @var string
    */
    const VERSION = '0.1.5';

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

    protected static $translator;

    public function __construct($public_path)
    {
        self::$instance = $this;
        $this->registerPaths($public_path);
        $this->registerBindings();
    }

    /**
     * @codeCoverageIgnore
     */
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
        $this->loadTranslations();
        $this->response->init($this->page);
        $this->session->save();
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

    public function loadTranslations()
    {
        $locale = $this->config->languages->getCurrent()->original;
        $path = ROOT_PATH . '/locale';
        $translationLoader = new FileLoader(new Filesystem, $path);
        $this->translator = new Translator($translationLoader, $locale);
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
     * Defines path constants
     * @return void
     */
    protected function registerPaths($public_path)
    {
        define('DS', DIRECTORY_SEPARATOR);
        define('CORE_PATH', __DIR__);
        define('PUBLIC_PATH', $public_path);
        define('ROOT_PATH', realpath(PUBLIC_PATH . DS . '..'));
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
        $this->singleton('session', '\\Kabas\\Session\\Manager');
        $this->singleton('config', '\\Kabas\\Config\\Container');
        $this->singleton('fields', '\\Kabas\\Fields\\Container');
        $this->singleton('router', function($app) {
            return new Router(new UrlWorker);
        });
        $this->singleton('content', '\\Kabas\\Content\\Container');
        $this->singleton('request', '\\Kabas\\Http\\Request');
        $this->singleton('response', '\\Kabas\\Http\\Response');
        $this->singleton('themes', '\\Kabas\\Themes\\Container');
    }

}
