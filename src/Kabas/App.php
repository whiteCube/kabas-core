<?php

namespace Kabas;

use \Illuminate\Container\Container;

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
     * Turns on or off any output
     * @var bool
     */
    public static $muted = false;

    /**
     * The instance of the app
     * @var Kabas
     */
    protected static $instance;

    /**
     * The Illuminate Translator instance
     * @var Illuminate\Translation\Translator
     */
    protected static $translator;

    /**
     * The classes to instantiate for the application to work
     * @var array
     */
    protected static $bootingSingletons = [
        'config' => \Kabas\Config\Container::class,
        'exceptions' => \Kabas\Exceptions\Handler::class,
        'session' => \Kabas\Session\Manager::class,
        'themes' => \Kabas\Themes\Container::class,
        'fields' => \Kabas\Fields\Container::class,
        'router' => \Kabas\Http\Router::class,
        'content' => \Kabas\Content\Container::class,
        'uploads' => \Kabas\Objects\Uploads\Container::class,
        'request' => \Kabas\Http\Request::class,
        'response' => \Kabas\Http\Response::class
    ];

    public function __construct($public_path)
    {
        self::$instance = $this;
        $this->registerPaths($public_path);
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
     * Initiates the required singletons 
     * in order to get the app rolling.
     * @return void
     */
    public function boot(array $singletons = null)
    {
        if(is_null($singletons)) $singletons = static::$bootingSingletons;
        $this->registerBindings($singletons);
    }

    /**
     * Start up the application
     * @return void
     */
    public function handle()
    {
        $this->loadAliases();
        $this->themes->loadCurrent();
        $this->router->capture()->load()->setCurrent();
        $this->content->parse();
        $this->page = $this->router->getCurrent()->page;
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
        if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        if(!defined('CORE_PATH')) define('CORE_PATH', __DIR__);
        if(!defined('PUBLIC_PATH')) define('PUBLIC_PATH', $public_path);
        if(!defined('ROOT_PATH')) define('ROOT_PATH', realpath(PUBLIC_PATH . DS . '..'));
        if(!defined('CONTENT_PATH')) define('CONTENT_PATH', ROOT_PATH . DS . 'content');
        if(!defined('STORAGE_PATH')) define('STORAGE_PATH', ROOT_PATH . DS . 'storage');
        if(!defined('SHARED_DIR')) define('SHARED_DIR', 'shared');
        if(!defined('CONFIG_PATH')) define('CONFIG_PATH', ROOT_PATH . DS . 'config');
        if(!defined('THEMES_PATH')) define('THEMES_PATH', ROOT_PATH . DS . 'themes');
    }

    /**
     * Defines given app singletons
     * @param array $singletons
     * @return void
     */
    protected function registerBindings(array $singletons)
    {
        foreach ($singletons as $name => $class) {
            $this->singleton($name, $class);
            if(method_exists($this[$name], 'boot')) $this[$name]->boot();
        }
    }

    static function preventFurtherOutput($muted = true)
    {
        self::$muted = $muted;
    }

}
