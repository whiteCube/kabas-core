<?php 

namespace Tests\Http;

use Kabas\Http\Route;
use Kabas\Http\Router;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication();
    }

    /** @test */
    public function can_be_instanciated_properly()
    {
        $this->assertInstanceOf(Router::class, new Router);
    }

    /** @test */
    public function can_load_routes_from_content_files()
    {
        $this->app->router->load();
        $this->assertCount(2, $this->app->router->getRoutes());
    }

    /** @test */
    public function can_get_a_route_by_page_name()
    {
        $this->app->router->load();
        $this->assertInstanceOf(Route::class, $this->app->router->getRouteByPage('about'));
    }

    /** @test */
    public function can_determine_if_a_route_exists()
    {
        $this->app->router->load();
        $this->assertTrue($this->app->router->routeExists('/about'));
        $this->assertFalse($this->app->router->routeExists('/test'));
    }

    /** @test */
    public function returns_false_when_trying_to_get_page_that_does_not_exist()
    {
        $this->assertFalse($this->app->router->getRouteByPage('test'));
    }

}