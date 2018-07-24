<?php

namespace Tests;

use Kabas\App;
use Kabas\Http\Routes\Router;
use PHPUnit\Framework\TestCase;
use Tests\DITesting\SomeClass;
use Tests\DITesting\SomeInstance;
use Kabas\Exceptions\NotFoundException;
use Theme\TheCapricorn\Providers\Package\SomeServiceProvider;

class AppTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_return_app_version()
    {
        $this->createApplication();
        $this->assertInternalType('string', $this->app->version());
    }

    /** @test */
    public function can_return_its_own_instance()
    {
        $this->createApplication();
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
        $this->createApplication();
        $instance = $this->app->make(SomeClass::class);
        $this->assertInstanceOf(Router::class, $instance->router);
        $this->assertInstanceOf(SomeInstance::class, $instance->instance);
    }

    /** @test */
    public function can_return_the_registered_service_providers()
    {
        $this->visit('/en/about', null, [SomeServiceProvider::class]);
        $this->assertTrue(is_array($this->app->getProviders()));
        $index = count($this->app->getProviders()) - 1;
        $this->assertInstanceOf(SomeServiceProvider::class, $this->app->getProviders()[$index]);
    }
}