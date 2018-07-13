<?php 

namespace Tests;

use Kabas\App;
use Kabas\Http\Router;
use Tests\DITesting\SomeClass;
use PHPUnit\Framework\TestCase;
use Tests\DITesting\SomeInstance;
use Kabas\Exceptions\NotFoundException;

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
        $this->expectException(NotFoundException::class);
        ob_end_clean();
        $this->visit('/doesnotexist');
    }

    /** @test */
    public function can_inject_dependencies()
    {
        $instance = $this->app->make(SomeClass::class);
        $this->assertInstanceOf(Router::class, $instance->router);
        $this->assertInstanceOf(SomeInstance::class, $instance->instance);
    }

}