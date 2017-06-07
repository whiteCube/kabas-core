<?php 

namespace Tests;

use Kabas\App;

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
	use CreatesApplication;

	protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
    	$this->app = $this->createApplication();
    	$_SERVER['SCRIPT_NAME'] = PUBLIC_PATH . '/index.php';
    	$_SERVER['HTTP_HOST'] = 'www.foo.com';
    	$_SERVER['REQUEST_URI'] = '/foo/bar';
    	$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4 ';
    	// $this->app->boot();
    }

    /** @test */
    public function can_return_app_version()
    {
        $this->assertInternalType('string', $this->app->version());
    }

    /** @test */
    public function can_return_its_own_instance()
    {
        $this->assertInstanceOf(App::class, App::getInstance());
    }

}