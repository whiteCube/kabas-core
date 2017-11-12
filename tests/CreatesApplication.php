<?php 

namespace Tests;

use Kabas\App;

trait CreatesApplication {

    use HandlesOutput;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public $app;

    public function createApplication(array $singletonsToBoot = null)
    {
        $this->alterGlobalServer();
        $this->app = new App(__DIR__ . '/TestTheme/public');
        $this->app->boot($singletonsToBoot ?? $this->getDefaultSingletons());
    }

    public function createMinimalContentApplicationForRoute($route)
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
            'themes' => \Kabas\Themes\Container::class,
            'fields' => \Kabas\Fields\Container::class,
            'content' => \Kabas\Content\Container::class,
            'uploads' => \Kabas\Objects\Uploads\Container::class,
            'router' => \Kabas\Http\Router::class,
            'response' => \Kabas\Http\Response::class,
        ]);
        $this->setPageRoute($route);
        $this->app->router->load()->setCurrent();
    }

    protected function getDefaultSingletons()
    {
        return [
            'session' => \Kabas\Session\Manager::class,
            'config' => \Kabas\Config\Container::class,
            'themes' => \Kabas\Themes\Container::class,
            'fields' => \Kabas\Fields\Container::class,
            'uploads' => \Kabas\Objects\Uploads\Container::class,
            'router' => \Kabas\Http\Router::class,
            'content' => \Kabas\Content\Container::class,
            'request' => \Kabas\Http\Request::class,
            'response' => \Kabas\Http\Response::class
        ];
    }

    public function alterGlobalServer()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['HTTP_HOST'] = 'www.foo.com';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en-GB,en;q=0.8,en-US;q=0.6,en;q=0.4 ';
    }

    public function setPageRoute($route)
    {
        $_SERVER['REQUEST_URI'] = $route;
        $this->app->router->boot();
    }

    public function visit($route)
    {
        $this->setPageRoute($route);
        $this->result = $this->catch(function(){
            $this->app->handle();
        });
        return $this;
    }
}