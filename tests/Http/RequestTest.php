<?php 

namespace Tests\Http;

use Tests\CreatesApplication;
use Kabas\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    use CreatesApplication;

    protected $preserveGlobalState = false;
    protected $runTestInSeparateProcess = true;

    public function setUp()
    {
        $this->createApplication([
            'config' => \Kabas\Config\Container::class
        ]);
    }

    /** @test */
    public function can_be_properly_instanciated()
    {
        $req = new Request;
        $this->assertInstanceOf(Request::class, $req);
    }

    /** @test */
    public function can_check_if_request_is_post()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $req = new Request;
        $this->assertTrue($req->isPost());
        $this->assertFalse($req->isGet());
    }

    /** @test */
    public function can_check_if_request_is_get()
    {
        $req = new Request;
        $this->assertTrue($req->isGet());
        $this->assertFalse($req->isPost());
    }

    /** @test */
    public function can_return_the_request_method()
    {
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $req = new Request;
        $this->assertEquals('PATCH', $req->getMethod());
    }

    /** @test */
    public function can_return_query_object()
    {
        $req = new Request;
        $this->assertInstanceOf(\Kabas\Http\Request\Query::class, $req->getQuery());
    }

    /** @test */
    public function can_return_locale_object()
    {
        $req = new Request;
        $this->assertInstanceOf(\Kabas\Http\Request\Locale::class, $req->getLocale());
    }

}
