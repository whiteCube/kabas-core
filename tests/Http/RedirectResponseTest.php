<?php 

namespace Tests\Http;

use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Http\Responses\Redirect;

class RedirectResponseTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    protected function prepareApplication()
    {
        $this->createApplication([
            'fields' => \Kabas\Fields\Container::class,
            'config' => \Kabas\Config\Container::class,
            'router' => \Kabas\Http\Routes\Router::class,
            'uploads' => \Kabas\Objects\Uploads\Container::class,
            'content' => \Kabas\Content\Container::class,
            'themes' => \Kabas\Themes\Container::class
        ]);
        $this->app->router->load();
    }

    /** @test */
    public function can_be_properly_instanciated()
    {
        $resp = new Redirect('example');
        $this->assertInstanceOf(Redirect::class, $resp);
    }

    /** @test */
    public function can_redirect_to_intended_page()
    {
        $this->prepareApplication();
        $resp = new Redirect('about');
        $resp->run();
        $this->assertContains('Location: http://www.foo.com/en/about', xdebug_get_headers());
    }

}