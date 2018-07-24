<?php 

namespace Tests\Http;

use Kabas\Http\Routes\Route;
use Kabas\Http\Routes\Router;
use Kabas\Http\Routes\RouteRepository;
use Kabas\Http\Request;
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
            'router' => \Kabas\Http\Routes\Router::class,
            'uploads' => \Kabas\Objects\Uploads\Container::class,
            'fields' => \Kabas\Fields\Container::class,
            'themes' => \Kabas\Themes\Container::class
        ], $route);
        if(!$route) return $this->app->router->load();
        $this->app->router->load()->setCurrent();
    }

    /** @test */
    public function can_be_instanciated_properly()
    {
        $request = $this->createMock(Request::class);
        $repository = $this->createMock(RouteRepository::class);
        $this->assertInstanceOf(Router::class, new Router($request, $repository));
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
    public function can_return_the_current_route()
    {
        $this->bootBaseRouterApplication('/foo/bar');
        $this->assertInstanceOf(\Kabas\Http\Routes\Route::class, $this->app->router->getCurrent());
        $this->assertEquals('example', $this->app->router->getCurrent()->getName());
    }
}