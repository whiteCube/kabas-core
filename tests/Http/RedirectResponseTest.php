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

    /** @test */
    public function can_be_properly_instanciated()
    {
        $resp = new Redirect('example');
        $this->assertInstanceOf(Redirect::class, $resp);
    }

    /** @test */
    public function can_redirect_to_intended_page()
    {
        $this->createApplication();
        $this->visit('/foo/bar');
        $resp = new Redirect('about');
        $resp->run();
        $this->assertContains('Location: http://www.foo.com/en/about', xdebug_get_headers());
    }

}