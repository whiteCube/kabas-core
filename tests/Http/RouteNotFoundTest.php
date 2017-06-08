<?php 

namespace Tests\Http;

use Kabas\Http\RouteNotFound;
use PHPUnit\Framework\TestCase;

class RouteNotFoundTest extends TestCase
{

    /** @test */
    public function can_be_properly_instanciated()
    {
        $this->assertInstanceOf(RouteNotFound::class, new RouteNotFound);
    }

    /** @test */
    public function never_matches_any_routes()
    {
        $route = new RouteNotFound;
        $this->assertFalse($route->matches('/foo/bar', 'en'));
    }
}