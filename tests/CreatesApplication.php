<?php 

namespace Tests;

use Kabas\App;

trait CreatesApplication {

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public $result;

    public function createApplication()
    {
        $this->app = new App(__DIR__ . '/TestTheme/public');
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['HTTP_HOST'] = 'www.foo.com';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4 ';
    }

    public function visit($route)
    {
        $_SERVER['REQUEST_URI'] = $route;
        ob_start();
        $this->app->boot();
        $this->result = ob_get_clean();
        return $this;
    }

    public function see($string)
    {
        return $this->assertContains($string, $this->result);
    }
}