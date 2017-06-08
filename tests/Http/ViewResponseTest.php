<?php 

namespace Tests\Http;

use Tests\CreatesApplication;
use PHPUnit\Framework\TestCase;
use Kabas\Http\Responses\View as ViewResponse;

class ViewResponseTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    /** @test */
    public function can_be_properly_instanciated()
    {
        $resp = new ViewResponse('example', []);
        $this->assertInstanceOf(ViewResponse::class, $resp);
    }

    /** @test */
    public function can_return_a_view_instance()
    {
        $this->expectOutputRegex('/The about page/');
        $this->createApplication();
        $this->visit('/foo/bar');
        $item = new \stdClass;
        $item->fields = new \stdClass;
        $item->data = null;
        $item->directory = 'templates';
        $resp = new ViewResponse('about', $item);
        $resp->run();
    }

}