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

    protected function bootBaseRouterApplication($route = null)
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
            'content' => \Kabas\Content\Container::class,
            'router' => \Kabas\Http\Router::class,
            'uploads' => \Kabas\Objects\Uploads\Container::class,
            'fields' => \Kabas\Fields\Container::class,
            'themes' => \Kabas\Themes\Container::class
        ]);
        if(!$route) return $this->app->router->capture()->load();
        $this->setPageRoute($route);
        $this->app->router->capture()->load()->setCurrent();
    }

    /** @test */
    public function can_be_instanciated_properly()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
        $this->assertInstanceOf(Router::class, new Router(new UrlWorker));
    }

    /** @test */
    public function can_load_routes_from_content_files()
    {
        $this->bootBaseRouterApplication();
        $this->assertCount(5, $this->app->router->getRoutes());
    }

    /** @test */
    public function can_get_a_route_by_page_name()
    {
        $this->bootBaseRouterApplication();
        $this->assertInstanceOf(Route::class, $this->app->router->getRouteByPage('about'));
    }

    /** @test */
    public function can_determine_if_a_route_exists()
    {
        $this->bootBaseRouterApplication();
        $this->assertTrue($this->app->router->routeExists('about'));
        $this->assertTrue($this->app->router->routeExists('/about'));
        $this->assertFalse($this->app->router->routeExists('/test'));
    }

    /** @test */
    public function can_determine_if_a_route_with_params_exists()
    {
        $this->bootBaseRouterApplication();
        $this->assertTrue($this->app->router->routeExists('params/foo'));
        $this->assertTrue($this->app->router->routeExists('/params/foo'));
        $this->assertFalse($this->app->router->routeExists('/params/foo/bar'));
    }

    /** @test */
    public function can_determine_if_a_route_with_optional_params_exists()
    {
        $this->bootBaseRouterApplication();
        $this->assertTrue($this->app->router->routeExists('optional'));
        $this->assertTrue($this->app->router->routeExists('/optional'));
        $this->assertTrue($this->app->router->routeExists('/optional/foo'));
        $this->assertTrue($this->app->router->routeExists('/optional/foo/bar'));
        $this->assertFalse($this->app->router->routeExists('/optional/foo/bar/test'));
    }

    /** @test */
    public function returns_false_when_trying_to_get_page_that_does_not_exist()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
            'router' => \Kabas\Http\Router::class,
        ]);
        $this->assertFalse($this->app->router->getRouteByPage('test'));
    }

    /** @test */
    public function can_return_the_current_route()
    {
        $this->bootBaseRouterApplication('/foo/bar');
        $this->assertEquals('/foo/bar', $this->app->router->getRoute());
    }
}