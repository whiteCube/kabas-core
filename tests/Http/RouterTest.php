<?php 

namespace Tests\Http;

use Kabas\Http\Route;
use Kabas\Http\Router;
use Kabas\Http\UrlWorker;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_be_instanciated_properly()
    {
        $this->createApplication();
        $this->assertInstanceOf(Router::class, new Router(new UrlWorker));
    }

    /** @test */
    public function can_load_routes_from_content_files()
    {
        $this->createApplication();
        $this->app->router->load();
        $this->assertCount(3, $this->app->router->getRoutes());
    }

    /** @test */
    public function can_get_a_route_by_page_name()
    {
        $this->createApplication();
        $this->app->router->load();
        $this->assertInstanceOf(Route::class, $this->app->router->getRouteByPage('about'));
    }

    /** @test */
    public function can_determine_if_a_route_exists()
    {
        $this->createApplication();
        $this->app->router->load();
        $this->assertTrue($this->app->router->routeExists('/about'));
        $this->assertFalse($this->app->router->routeExists('/test'));
    }

    /** @test */
    public function returns_false_when_trying_to_get_page_that_does_not_exist()
    {
        $this->createApplication();
        $this->assertFalse($this->app->router->getRouteByPage('test'));
    }

    /** @test */
    public function can_return_the_current_route()
    {
        $this->createApplication();
        $this->visit('/foo/bar');
        $this->assertEquals('/foo/bar', $this->app->router->getRoute());
    }


}