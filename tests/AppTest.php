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
    	$this->createApplication();
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

    /** @test */
    public function can_render_a_page()
    {
    	$this->visit('/foo/bar')->see('An incredible test page');
    }

    /** @test */
    public function can_return_a_four_o_four()
    {
        $this->visit('/doesnotexist')->see('404');
    }

}