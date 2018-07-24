<?php 

namespace Tests\Http;

use Kabas\Http\Routes\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function generateRoute($definition, $namespace = null)
    {
        return new Route($namespace, 'foo', $definition);
    }

    /** @test */
    public function can_be_instanciated_properly()
    {
        $this->assertInstanceOf(Route::class, $this->generateRoute(['en-GB' => '/foo/{bar}']));
    }

    /** @test */
    public function can_check_if_route_matches_another()
    {
        $route = $this->generateRoute(['en-GB' => 'foo/{bar}']);
        $slashRoute = $this->generateRoute(['en-GB' => '/foo/{bar}/']);
        $multilangRoute = $this->generateRoute(['en-GB' => '/test', 'fr-FR' => '/foo/{bar}']);

        $locale = 'en-GB';

        $this->assertFalse($route->matches('/test', $locale));
        $this->assertTrue($route->matches('/foo/bar', $locale));
        $this->assertTrue($route->matches('/foo/bar/', $locale));
        $this->assertTrue($route->matches('foo/bar', $locale));
        $this->assertTrue($slashRoute->matches('/foo/bar', $locale));
        $this->assertTrue($slashRoute->matches('/foo/bar/', $locale));
        $this->assertTrue($slashRoute->matches('foo/bar', $locale));
        $this->assertTrue($multilangRoute->matches('/test', $locale));
    }

    /** @test */
    public function can_return_parameters_for_the_current_route()
    {  
        $route = $this->generateRoute(['en-GB' => '/foo/{bar}']);
        $route->gatherParameters('/foo/hello', 'en-GB');
        $parameters = $route->getParameters();
        $this->assertEquals('hello', $parameters['bar']);
    }

    /** @test */
    public function can_generate_proper_namespaced_signatures()
    {
        $unnamespaced = $this->generateRoute(['en-GB' => '/foo/{bar}'], null);
        $namespaced = $this->generateRoute(['en-GB' => '/foo/{bar}'], 'test');

        $this->assertEquals('foo', $unnamespaced->getSignature());
        $this->assertEquals('test.foo', $namespaced->getSignature());
    }

}