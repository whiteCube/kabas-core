<?php 

namespace Tests\Http;

use Kabas\Http\Routes\Cache;
use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Http\Routes\RouteRepository;
use Kabas\Http\Routes\Route;

class RouteRepositoryTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_load_routes_from_content_files()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
            'content' => \Kabas\Content\Container::class,
            'uploads' => \Kabas\Objects\Uploads\Container::class,
            'fields' => \Kabas\Fields\Container::class,
            'themes' => \Kabas\Themes\Container::class
        ]);
        $cache = $this->createMock(Cache::class);
        $repository = new RouteRepository($cache);
        $repository->loadFromContent();
        $this->assertInstanceOf(Route::class, $repository->get('about'));
        $this->assertNull($repository->get('test-does-not-exist'));
    }

    /** @test */
    public function can_register_new_route_manually()
    {
        $cache = $this->createMock(Cache::class);
        $repository = new RouteRepository($cache);
        $repository->register('foo', 'bar', ['en-GB' => '/foo/{bar}']);
        $this->assertInstanceOf(Route::class, $repository->get('foo.bar'));
    }

}