<?php 

namespace Tests\Http;

use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Http\Routes\RouteRepository;
use Kabas\Http\Routes\Route;

class RouteRepositoryTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    protected function bootBase()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class,
            'content' => \Kabas\Content\Container::class,
            'uploads' => \Kabas\Objects\Uploads\Container::class,
            'fields' => \Kabas\Fields\Container::class,
            'themes' => \Kabas\Themes\Container::class
        ]);
    }

    /** @test */
    public function can_load_routes_from_content_files()
    {
        $this->bootBase();
        $repository = new RouteRepository();
        $repository->loadFromContent();
        $this->assertInstanceOf(Route::class, $repository->get('about'));
    }

    /** @test */
    public function can_register_new_route_manually()
    {
        $repository = new RouteRepository();
        $repository->register('foo', 'bar', ['en-GB' => '/foo/{bar}']);
        $this->assertInstanceOf(Route::class, $repository->get('foo.bar'));
    }
}